<?php
namespace CFX\SDK\Brokerage;

class AssetsClient extends \CFX\Persistence\Rest\AbstractDatasource {
    protected static $resourceType = 'assets';

    public function create(array $data=null) {
        return new \CFX\Exchange\Asset($this, $data);
    }
}

