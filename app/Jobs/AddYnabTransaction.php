<?php

namespace App\Jobs;

use App\YnabApi;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AddYnabTransaction implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $accessToken = '';
    private $transaction = [];

    public function __construct(array $transaction, string $accessToken)
    {
        $this->transaction = $transaction;
        $this->accessToken = $accessToken;
    }

    public function handle()
    {
        $ynabApi = new YnabApi($this->accessToken);
        $ynabApi->addTransaction(
            $this->transaction['budget_id'],
            $this->transaction['account_id'],
            $this->transaction['date'],
            $this->transaction['amount'],
            $this->transaction['payee'],
            $this->transaction['notes']
        );
    }
}
