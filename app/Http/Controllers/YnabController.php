<?php

namespace App\Http\Controllers;

use App\OAuth\Ynab;
use App\YnabApi;
use Illuminate\Http\Request;

class YnabController extends Controller
{
    public function reset(Request $request)
    {
        $request->session()->forget('ynab');
        $request->session()->reflash();

        return redirect('/');
    }

    public function resetBudget(Request $request)
    {
        $request->session()->forget('ynab.chosen_budget');
        $request->session()->forget('ynab.chosen_account');
        $request->session()->reflash();

        return redirect('/ynab/choose-budget');
    }

    public function resetAccount(Request $request)
    {
        $request->session()->forget('ynab.chosen_account');
        $request->session()->reflash();

        return redirect('/ynab/choose-account');
    }

    public function auth(Request $request, Ynab $ynab)
    {
        $authUrl = $ynab->getAuthorizationUrl();
        $request->session()->put('ynab', [
            'state' => $ynab->getState()
        ]);

        return redirect($authUrl);
    }


    public function redirect(Request $request, Ynab $ynab)
    {
        $state = $request->query('state');
        if (!$state) {
            flash('Something went wrong with your state, please try again.', 'warning');

            return redirect('/ynab/reset');
        }

        $code = $request->input('code');
        if (!$code) {
            flash('Something went wrong with your state, please try again.', 'warning');

            return redirect('/ynab/reset');
        }

        try {
            $accessToken = $ynab->getAccessToken('authorization_code', [
                'code' => $code
            ]);
        } catch (IdentityProviderException $e) {
            flash(
                'Something went horrible wrong linking YNAB, please try again - ' . $e->getMessage(),
                'warning'
            );

            return redirect('/ynab/reset');
        }

        $ynabUser = $ynab->getResourceOwner($accessToken);

        $request->session()->put('ynab', [
            'access_token' => $accessToken->getToken(),
            'refresh_token' => $accessToken->getRefreshToken(),
            'expires' => $accessToken->getExpires(),
            'whoami' => [
                'user_id' => $ynabUser->getId(),
            ]
        ]);

        return redirect('/ynab/choose-budget');
    }

    public function chooseBudget(Request $request)
    {
        $accessToken = $request->session()->get('ynab.access_token');
        $expires = $request->session()->get('ynab.expires');

        if ($expires < time()) {
            flash('Access token is invalid, cannot choose account', 'warning');

            return redirect('/ynab/reset');
        }

        $ynabApi = new YnabApi($accessToken);

        try {
            $budgets = $ynabApi->getBudgets();
        } catch (\Exception $e) {
            flash('Failed to retrieve budgets, please try again', 'warning');

            return redirect('/');
        }

        $request->session()->put('ynab.budgets', $budgets);

        if (count($budgets) === 1) {
            // only have one budget, they don't need to choose - we'll choose for them
            $request->session()->put('ynab.chosen_budget.id', $budgets[0]['id']);
            $request->session()->put('ynab.chosen_budget.name', $budgets[0]['name']);

            return redirect('/');
        }

        return view('ynab.choose-budget', [
            'budgets' => $budgets
        ]);
    }


    public function chosenBudget(Request $request, string $budget_id)
    {
        $budgets = $request->session()->get('ynab.budgets');
        if (!$budgets) {
            flash('You aren\'t ready to choose a YNAB budget, please try again', 'warning');

            return redirect('/ynab/reset');
        }

        foreach ($budgets as $budget) {
            if ($budget['id'] == $budget_id) {
                $request->session()->put('ynab.chosen_budget.id', $budget['id']);
                $request->session()->put('ynab.chosen_budget.name', $budget['name']);

                flash('YNAB budget successfully chosen', 'success');

                return redirect('/ynab/choose-account');
            }
        }

        flash('Invalid YNAB budget chosen, please try again', 'warning');

        return redirect('/');
    }


    public function chooseAccount(Request $request)
    {
        $accessToken = $request->session()->get('ynab.access_token');
        $expires = $request->session()->get('ynab.expires');

        if ($expires < time()) {
            flash('Access token is invalid, cannot choose account', 'warning');

            return redirect('/ynab/reset');
        }

        $budgetId = $request->session()->get('ynab.chosen_budget.id');
        if (!$budgetId) {
            flash('Must choose a budget first', 'warning');

            return redirect('/ynab/choose-budget');
        }

        $ynabApi = new YnabApi($accessToken);

        try {
            $accounts = $ynabApi->getAccounts($budgetId);
        } catch (\Exception $e) {
            flash('Failed to retrieve accounts, please try again', 'warning');

            return redirect('/');
        }

        $request->session()->put('ynab.accounts', $accounts);

        if (count($accounts) === 1) {
            // only have one account, they don't need to choose - we'll choose for them
            $request->session()->put('ynab.chosen_account.id', $accounts[0]['id']);
            $request->session()->put('ynab.chosen_account.name', $accounts[0]['name']);

            return redirect('/');
        }

        return view('ynab.choose-account', [
            'accounts' => $accounts
        ]);
    }


    public function chosenAccount(Request $request, string $account_id)
    {
        $accounts = $request->session()->get('ynab.accounts');
        if (!$accounts) {
            flash('You aren\'t ready to choose a YNAB budget, please try again', 'warning');

            return redirect('/ynab/reset');
        }

        foreach ($accounts as $account) {
            if ($account['id'] == $account_id) {
                $request->session()->put('ynab.chosen_account.id', $account['id']);
                $request->session()->put('ynab.chosen_account.name', $account['name']);

                flash('YNAB account successfully chosen, ready to sync!', 'success');

                return redirect('/');
            }
        }

        flash('Invalid YNAB account chosen, please try again', 'warning');

        return redirect('/');
    }
}
