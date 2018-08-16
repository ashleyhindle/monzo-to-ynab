<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Webhook extends Model
{
    public $fillable = [
        'monzo_account_id',
        'monzo_webhook_id',
        'ynab_refresh_token',
        'ynab_budget_id',
        'ynab_account_id',
        'count',
    ];
}
