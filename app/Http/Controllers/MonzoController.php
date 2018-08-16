<?php

namespace App\Http\Controllers;

use App\MonzoApi;
use App\OAuth\Monzo;
use App\Webhook;
use Carbon\Carbon;
use Illuminate\Encryption\Encrypter;
use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class MonzoController extends Controller
{
    public function reset(Request $request)
    {
        $request->session()->forget('monzo');
        $request->session()->reflash();

        return redirect('/');
    }

    public function resetAccount(Request $request)
    {
        $request->session()->forget('monzo.chosen_account');
        $request->session()->reflash();

        return redirect('/monzo/choose-account');
    }

    public function auth(Request $request, Monzo $monzo)
    {
        $authUrl = $monzo->getAuthorizationUrl();
        $request->session()->put('monzo', [
            'state' => $monzo->getState()
        ]);

        return redirect($authUrl);
    }

    public function redirect(Request $request, Monzo $monzo)
    {
        $state = $request->query('state');
        if (!$state) {
            flash('Something went wrong with your state, please try again.', 'warning');

            return redirect('/monzo/reset');
        }

        $code = $request->input('code');
        if (!$code) {
            flash('Something went wrong with your state, please try again.', 'warning');

            return redirect('/monzo/reset');
        }

        try {
            $accessToken = $monzo->getAccessToken('authorization_code', [
                'code' => $code
            ]);
        } catch (IdentityProviderException $e) {
            flash(
                'Something went horrible wrong linking Monzo, please try again - ' . $e->getMessage(),
                'warning'
            );

            return redirect('/monzo/reset');
        }

        $monzoUser = $monzo->getResourceOwner($accessToken);

        $request->session()->put('monzo', [
            'access_token' => $accessToken->getToken(),
            'refresh_token' => $accessToken->getRefreshToken(),
            'expires' => $accessToken->getExpires(),
            'whoami' => [
                'client_id' => $monzoUser->getClientId(),
                'user_id' => $monzoUser->getUserId(),
            ]
        ]);

        return redirect('/monzo/choose-account');
    }

    public function choose(Request $request)
    {
        $accessToken = $request->session()->get('monzo.access_token');
        $expires = $request->session()->get('monzo.expires');

        if ($expires < time()) {
            flash('Access token is invalid, cannot choose account', 'warning');

            return redirect('/monzo/reset');
        }

        $monzoApi = new MonzoApi($accessToken);

        try {
            $accounts = $monzoApi->getAccounts();
        } catch (\Exception $e) {
            flash('Failed to retrieve accounts, please try again', 'warning');

            return redirect('/'); // TODO: change to /monzo/reset
        }

        $request->session()->put('monzo.accounts', $accounts);

        if (count($accounts) === 1) {
            // only have one account, they don't need to choose - we'll choose for them
            return redirect('/monzo/choose-account/' . $accounts[0]['id']);
        }

        return view('monzo.choose-account', [
            'accounts' => $accounts
        ]);
    }

    public function chosen(Request $request, string $account_id)
    {
        $accounts = $request->session()->get('monzo.accounts');
        if (!$accounts) {
            flash('You aren\'t ready to choose a Monzo account, please try again', 'warning');

            return redirect('/monzo/reset');
        }

        $duplicate = (Webhook::where('monzo_account_id', $account_id)->count() > 0) ? true : false;
        $request->session()->put('monzo.duplicate', $duplicate);

        foreach ($accounts as $account) {
            if ($account['id'] == $account_id) {
                $request->session()->put('monzo.chosen_account.id', $account['id']);
                $request->session()->put('monzo.chosen_account.name', $account['description']);

                flash('Monzo account successfully chosen, YNAB now?', 'success');
                return redirect('/');
            }
        }

        flash('Invalid Monzo account chosen, please try again', 'warning');

        return redirect('/');
    }

    public function setupWebhook(Request $request)
    {
        $monzoChosenAccount = $request->session()->get('monzo.chosen_account.id');
        $monzoExpired = $request->session()->get('monzo.expires') <= time();
        $monzoAccessToken = $request->session()->get('monzo.access_token');

        $ynabExpired = $request->session()->get('ynab.expires') <= time();
        $ynabChosenBudget = $request->session()->get('ynab.chosen_budget.id');
        $ynabChosenAccount = $request->session()->get('ynab.chosen_account.id');
        $ynabRefreshToken = $request->session()->get('ynab.refresh_token');

        $monzoOk = $monzoChosenAccount && $monzoAccessToken && !$monzoExpired;
        $ynabOk = $ynabChosenBudget && $ynabChosenAccount && $ynabRefreshToken && !$ynabExpired;

        // Something isn't setup correctly, we're not ready to setup the webhook
        if (!$monzoOk || !$ynabOk) {
            flash('Sorry, something isn\'t fully setup and we aren\'t ready to start syncing yet, please try again', 'warning');

            return redirect('/');
        }

        $encryptedRefreshToken = encrypt($ynabRefreshToken);
        $webhookUrl = url('/monzo/webhook');

        // Delete any existing webhook setups
        Webhook::where('monzo_account_id', $monzoChosenAccount)->delete();

        $webhook = new Webhook([
            'monzo_account_id' => $monzoChosenAccount,
            'ynab_refresh_token' => $encryptedRefreshToken,
            'ynab_budget_id' => $ynabChosenBudget,
            'ynab_account_id' => $ynabChosenAccount,
            'monzo_webhook_id' => 'not-setup',
            'count' => 0,
        ]);

        $saved = $webhook->save();

        if (!$webhook || !$saved) {
            flash('Sorry! We failed to start syncing because of our badly behaved database', 'warning');

            return redirect('/');
        }

        $monzoApi = new MonzoApi($monzoAccessToken);
        // webhookId is MONZO's webhook_id, not our 'webhooks.id' column in MySQL
        try {
            $webhookId = $monzoApi->registerWebhook($monzoChosenAccount, $webhookUrl);
        } catch (\Exception $e) {
            $webhookId = false;
        }

        if (!$webhookId) {
            flash('Sorry! We failed to setup the syncing magic with Monzo, please try again later', 'warning');
            $webhook->delete();

            return redirect('/');
        }

        $webhook->monzo_webhook_id = $webhookId;
        $webhook->save();

        flash('All done! Syncing will now commence, give it a try!', 'success');

        return redirect('/done');
    }

    public function cancelMessage(Request $request)
    {
        flash('Please link your Monzo account in the left panel, then you will be given the option to stop all syncing', 'danger');

        return redirect('/');
    }

    public function cancel(Request $request, string $account_id)
    {
        // Step 1: Delete all 'webhooks' entries from MySQL for this account id
        // Step 2: Get all monzo webhooks for this account, get the ones that are ours (how do we identify these?)
        // Step 3: Loop through webhooks and delete them
        // Step 4: Inform user, all done

        return redirect('/');
    }
}
