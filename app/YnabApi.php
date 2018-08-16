<?php

namespace App;

class YnabApi
{
    private $baseUrl = "https://api.youneedabudget.com/v1/";
    private $accessToken;
    private $curl;

    public function __construct(string $accessToken)
    {
        $this->accessToken = $accessToken;
        $this->curl = curl_init();
        curl_setopt_array($this->curl, [
            CURLOPT_HTTPHEADER => ["Authorization: Bearer {$this->accessToken}"],
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
        ]);
    }

    public function getBudgets(): array
    {
        curl_setopt($this->curl, CURLOPT_URL, $this->baseUrl . "budgets");

        $response = curl_exec($this->curl);
        if (!$response || curl_getinfo($this->curl, CURLINFO_HTTP_CODE) !== 200) {
            throw new \Exception('Failed to get budgets: ' . curl_error($this->curl));
        }

        $budgets = json_decode($response, true)['data']['budgets'];
        return $budgets;
    }

    public function getAccounts(string $budgetId): array
    {
        curl_setopt($this->curl, CURLOPT_URL, $this->baseUrl . "budgets/{$budgetId}/accounts");

        $response = curl_exec($this->curl);
        if (!$response || curl_getinfo($this->curl, CURLINFO_HTTP_CODE) !== 200) {
            throw new \Exception('Failed to get accounts: ' . curl_error($this->curl));
        }

        $accounts = json_decode($response, true)['data']['accounts'];
        return $accounts;
    }
}
