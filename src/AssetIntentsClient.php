<?php
namespace CFX\SDK\Brokerage;

class AssetIntentsClient extends \CFX\Persistence\Rest\AbstractDatasource {
    protected static $resourceType = 'assetIntents';

    public function create(array $data=null) {
        return new \CFX\Brokerage\AssetIntent($this, $data);
    }
}

