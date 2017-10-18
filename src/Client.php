<?php
namespace CFX\SDK\Exchange;

class Client extends \CFX\SDK\BaseClient {
    protected static $apiName = 'exchange';
    protected static $apiVersion = '0';
    protected $subclients = ['assets'];

    protected function instantiateSubclient($name) {
        if ($name == 'assets') return new AssetsClient($this);

        return parent::instantiateSubclient($name);
    }
}

