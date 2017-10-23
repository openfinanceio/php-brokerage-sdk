<?php
namespace CFX\SDK\Brokerage;

class OrderIntentsClient extends \CFX\Persistence\Rest\AbstractDatasource {
    protected static $resourceType = 'orderIntents';

    public function create(array $data=null) {
        return new \CFX\Brokerage\OrderIntent($this, $data);
    }
}

