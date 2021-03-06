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

    /**
     * Get webhooks for the chosen accounts.
     *
     * @param string $accountId
     * @param bool $oursOnly - Webhooks we have setup
     * @return array
     * @throws \Exception
     */
    public function getWebhooks(string $accountId, bool $oursOnly=true): array
    {
        curl_setopt($this->curl, CURLOPT_URL, $this->baseUrl . "webhooks?account_id=" . $accountId);

        $response = curl_exec($this->curl);
        if (!$response || curl_getinfo($this->curl, CURLINFO_HTTP_CODE) !== 200) {
            throw new \Exception('Failed to get accounts: ' . curl_error($this->curl) . $response . '--' .  curl_getinfo($this->curl, CURLINFO_HTTP_CODE) );
        }

        $webhooks = collect(json_decode($response, true)['webhooks']);

        $webhooks->filter(function ($webhook) use($accountId) {
           return $webhook['account_id'] == $accountId;
        });

        if ($oursOnly) {
            $webhooks->filter(function ($webhook) {
                return ends_with($webhook['url'], 'setupByMonzoToYnab');
            });
        }

        return $webhooks->values()->toArray();
    }

    public function registerWebhook(string $accountId, string $url): bool
    {
        $data = [
            'account_id' => $accountId,
            'url' => $url,
        ];

        curl_setopt($this->curl, CURLOPT_URL, $this->baseUrl . "webhooks");
        curl_setopt($this->curl, CURLOPT_POST, true);
        curl_setopt($this->curl, CURLOPT_POSTFIELDS, http_build_query($data));

        $response = curl_exec($this->curl);
        if (!$response || curl_getinfo($this->curl, CURLINFO_HTTP_CODE) !== 200) {
            throw new \Exception('Failed to register webhook: ' . curl_error($this->curl) . '-' . $response);
        }

        $webhook = json_decode($response, true)['webhook'];

        return $webhook['id'];
    }

    public function deleteWebhook(string $webhook_id)
    {
        curl_setopt($this->curl, CURLOPT_URL, $this->baseUrl . "webhooks/{$webhook_id}");
        curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, "DELETE");

        $response = curl_exec($this->curl);
        if (!$response || curl_getinfo($this->curl, CURLINFO_HTTP_CODE) !== 200) {
            throw new \Exception('Failed to delete webhook: ' . curl_error($this->curl) . '-' . $response);
        }

        return true;
    }
}
