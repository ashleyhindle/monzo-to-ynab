<?php
namespace App\OAuth;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class YnabUser implements ResourceOwnerInterface
{
    private $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function getId()
    {
        return $this->data['id'];
    }

    public function toArray()
    {
        return $this->data;
    }
}
