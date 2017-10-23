<?php
namespace CFX\SDK\Brokerage;

class Client extends \CFX\Persistence\Rest\AbstractDataContext {
    protected static $apiName = 'brokerage';
    protected static $apiVersion = '2';

    protected function instantiateDatasource($name) {
        if ($name == 'assets') return new \CFX\Persistence\Rest\GenericDatasource($this, $name, "\\CFX\\Exchange\\Asset");
        if ($name == 'assetIntents') return new \CFX\Persistence\Rest\GenericDatasource($this, $name, "\\CFX\\Brokerage\\AssetIntent");
        if ($name == 'orders') return new \CFX\Persistence\Rest\GenericDatasource($this, $name, "\\CFX\\Exchange\\Order");
        if ($name == 'orderIntents') return new \CFX\Persistence\Rest\GenericDatasource($this, $name, "\\CFX\\Brokerage\\OrderIntent");
        if ($name == 'users') return new \CFX\Persistence\Rest\GenericDatasource($this, $name, "\\CFX\\Brokerage\\User");

        return parent::instantiateDatasource($name);
    }
}

