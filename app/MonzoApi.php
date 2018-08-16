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
        if (!$response || curl_getinfo($this->curl, CURLINFO_HTTP_CODE) !== 200) {
            throw new \Exception('Failed to get accounts: ' . curl_error($this->curl));
        }

        $accounts = json_decode($response, true)['accounts'];

        foreach ($accounts as $key => $account) {
            // Is this a joint account?
            if (substr($account['type'], -5) == 'joint') {
                foreach ($account['owners'] as $owner) {
                    // Improve description: Joint account between user_00009DTyDkxxxxxxxx and user_000096Faxxxxxxxx => Joint account between Ashley and Sarah
                    $accounts[$key]['description'] = str_replace($owner['user_id'], $owner['preferred_first_name'], $accounts[$key]['description']);
                }
            }
        }

        // All accounts, not just open accounts
        if (!$openAccounts) {
            return $accounts;
        }

        return collect($accounts)->filter(function ($account) {
            return $account['closed'] === false;
        })->values()->toArray();
    }
}
