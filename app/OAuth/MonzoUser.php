<?php
namespace App\OAuth;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class MonzoUser implements ResourceOwnerInterface
{
    private $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getId()
    {
        return $this->getClientId();
    }

    public function getClientId()
    {
        return $this->data['client_id'];
    }

    public function getUserId()
    {
        return $this->data['user_id'];
    }

    public function toArray()
    {
        return $this->data;
    }
}
