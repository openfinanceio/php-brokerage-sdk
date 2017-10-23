<?php
namespace CFX\SDK\Brokerage;

class OrdersClient extends \CFX\Persistence\Rest\AbstractDatasource {
    protected static $resourceType = 'orders';

    public function create(array $data=null) {
        return new \CFX\Exchange\Order($this, $data);
    }
}

