<?php
namespace CFX\SDK\Brokerage;

class Client extends \CFX\SDK\BaseClient {
    protected static $apiName = 'exchange';
    protected static $apiVersion = '2';
    protected $subclients = ['assets', 'assetIntents', 'orders', 'orderIntents', 'users'];

    protected function instantiateSubclient($name) {
        if ($name == 'assets') return new AssetsClient($this);
        if ($name == 'assetIntents') return new AssetIntentsClient($this);
        if ($name == 'orders') return new OrdersClient($this);
        if ($name == 'orderIntents') return new OrderIntentsClient($this);
        if ($name == 'users') return new UsersClient($this);

        return parent::instantiateSubclient($name);
    }
}

