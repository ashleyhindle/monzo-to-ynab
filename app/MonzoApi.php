<?php

namespace App;

class MonzoApi
{
    private $baseUrl = "https://api.monzo.com/";
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

    public function getAccounts(bool $openAccounts = true): array
    {
        curl_setopt($this->curl, CURLOPT_URL, $this->baseUrl . "accounts");

        $response = curl_exec($this->curl);
        if (!$response) {
            throw new \Exception('Failed to get accounts: ' . curl_error($this->curl));
        }

        $accounts = json_decode($response, true)['accounts'];

        // All accounts, not just open accounts
        if (!$openAccounts) {
            return $accounts;
        }

        return collect($accounts)->filter(function ($account) {
            return $account['closed'] === false;
        })->toArray();
    }
}
