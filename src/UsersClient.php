<?php
namespace CFX\SDK\Brokerage;

class UsersClient extends \CFX\Persistence\Rest\AbstractDatasource {
    protected static $resourceType = 'users';

    public function create(array $data=null) {
        return new \CFX\Brokerage\User($this, $data);
    }
}

