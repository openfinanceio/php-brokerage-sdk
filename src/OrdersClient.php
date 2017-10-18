<?php
namespace CFX\SDK\Brokerage;

class OrdersClient extends \CFX\SDK\BaseSubclient {
    protected static $resourceType = 'orders';

    public function get($q=null) {
        $opts = [];
        $endpoint = "/".static::$resourceType;
        if ($q) {
            if (substr($q, 0, 3) != 'id=' || strpos($q, ' ') !== false) throw new \RuntimeException("Programmer: for now, only id queries are accepted. Please pass `id=[order-id]` if you'd like to query a specific order. Otherwise, just get all orders and filter them yourself.");
            $isCollection = false;
            $endpoint .= "/".substr($q, 3);
        } else {
            $isCollection = true;
        }

        $r = $this->cfxClient->sendRequest('GET', $endpoint, $opts);
        $obj = json_decode($r->getBody(), true);

        return $this->inflateData($obj, $isCollection);
    }

    protected function inflateData(array $obj, $isCollection) {
        $f = $this->cfxClient->getFactory();

        if (!$isCollection) $obj = [$obj];
        foreach($obj as $k => $o) $obj[$k] = $f->newOrder($o);
        return $isCollection ?
            $f->newJsonApiResourceCollection($obj) :
            $obj[0]
        ;
    }
}

