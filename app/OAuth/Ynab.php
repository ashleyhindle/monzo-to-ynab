<?php
namespace App\OAuth;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class Ynab extends AbstractProvider
{
    use BearerAuthorizationTrait;

    public function getBaseAuthorizationUrl()
    {
        return 'https://app.youneedabudget.com/oauth/authorize';
    }

    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://app.youneedabudget.com/oauth/token';
    }

    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return 'https://api.youneedabudget.com/v1/user';
    }

    protected function getDefaultScopes()
    {
        return [];
    }

    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() >= 400) {
            throw new IdentityProviderException(
                $response->getReasonPhrase(),
                $response->getStatusCode(),
                $response->getBody()
            );
        }
    }

    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new YnabUser($response['data']['user']);
    }

}
