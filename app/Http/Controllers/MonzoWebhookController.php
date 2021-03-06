<?php

namespace App\Http\Controllers;

use App\Jobs\AddYnabTransaction;
use App\OAuth\Ynab;
use App\Webhook;
use App\YnabApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/*
Example:
{
    "type": "transaction.created",
    "data": {
        "id": "tx_00009ZkaN9gi7YhesmY8PZ",
        "created": "2018-08-16T19:20:19.267Z",
        "description": "",
        "amount": -500,
        "fees": {},
        "currency": "GBP",
        "merchant": null,
        "notes": "",
        "metadata": {
            "p2p_initiator": "internal",
            "p2p_transfer_id": "p2p_00009ZkaN7abvGs4UWCvPl"
        },
        "labels": null,
        "account_balance": 0,
        "attachments": null,
        "international": null,
        "category": "general",
        "is_load": false,
        "settled": "2018-08-16T19:20:19.267Z",
        "local_amount": -500,
        "local_currency": "GBP",
        "updated": "2018-08-16T19:20:19.575Z",
        "account_id": "acc_00009NLDkS2AMllafZw6HB",
        "user_id": "user_000096FaXwhpMY8DoyIQML",
        "counterparty": {
            "account_id": "acc_00009YhMHrtIpmGr0cqk5J",
            "name": "Ashley Hindle & Sarah Hindle",
            "preferred_name": "Ashley Hindle & Sarah Hindle",
            "user_id": "anonuser_037784bfbccac909f74e30"
        },
        "scheme": "p2p_payment",
        "dedupe_id": "p2p-payment:acc_00009NLDkS2AMllafZw6HB:acc_00009NLDkS2AMllafZw6HBuser_000096FaXwhpMY8DoyIQML:4d1384d8-b310-43fb-b831-f3b1190b0691",
        "originator": true,
        "include_in_spending": true,
        "can_be_excluded_from_breakdown": true,
        "can_be_made_subscription": false,
        "can_split_the_bill": false
    }
}
*/
class MonzoWebhookController extends Controller
{
    public function webhook(Request $request, Ynab $ynabOauth)
    {
        $input = $request->input();
        $type = $input['type'];
        $data = $input['data'];

        if ($type != 'transaction.created') {
            return 'Thank you';
        }

        $webhook = Webhook::where('monzo_account_id', $data['account_id'])->first();
        if (!$webhook) {
            return 'Not for us, thank you very much.  No syncing setup.';
        }

        $newAccessToken = $ynabOauth->getAccessToken('refresh_token', [
            'refresh_token' => decrypt($webhook->ynab_refresh_token)
        ]);

        $webhook->ynab_refresh_token = encrypt($newAccessToken->getRefreshToken());
        $webhook->count++;
        $webhook->save();

        $payee = 'Unknown Payee';
        if (!empty($data['description'])) {
            $payee = $data['description'];
        }

        if (!empty($data['merchant']['name'])) {
            $payee = $data['merchant']['name'];
        }

        if (!empty($data['counterparty']['preferred_name'])) {
            $payee = $data['counterparty']['preferred_name'];
        }

        AddYnabTransaction::dispatch([
            'budget_id' => $webhook->ynab_budget_id,
            'account_id' => $webhook->ynab_account_id,
            'date' => new \DateTime($data['created']),
            'amount' => $data['amount']*10,
            'payee' => $payee,
            'notes' => $data['notes'] ?: ""
        ], $newAccessToken->getToken());

        return 'Thanks, perfect, sorted, done';
    }
}
