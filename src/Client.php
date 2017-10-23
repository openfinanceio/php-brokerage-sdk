<?php
namespace CFX\SDK\Brokerage;

class Client extends \CFX\Persistence\Rest\AbstractDataContext {
    protected static $apiName = 'brokerage';
    protected static $apiVersion = '2';

    protected function instantiateDatasource($name) {
        if ($name == 'assets') return new AssetsClient($this);
        if ($name == 'assetIntents') return new AssetIntentsClient($this);
        if ($name == 'orders') return new OrdersClient($this);
        if ($name == 'orderIntents') return new OrderIntentsClient($this);
        if ($name == 'users') return new UsersClient($this);

        return parent::instantiateDatasource($name);
    }
}

