<?php
namespace CFX\SDK\Exchange;

class AssetsClient extends \CFX\SDK\BaseSubclient {
    protected static $resourceType = 'assets';

    public function get($q=null) {
        $opts = [];
        $endpoint = "/".static::$resourceType;
        if ($q) {
            if (substr($q, 0, 3) != 'id=' || strpos($q, ' ') !== false) throw new \RuntimeException("Programmer: for now, only id queries are accepted. Please pass `id=[asset-symbol]` if you'd like to query a specific asset. Otherwise, just get all assets and filter them yourself.");
            $isCollection = false;
            $opts['query'] = ['symbol' => substr($q, 3)];
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
        foreach($obj as $k => $o) $obj[$k] = $f->assetFromV1Data($o);
        return $isCollection ?
            $f->newJsonApiResourceCollection($obj) :
            $obj[0]
        ;
    }
}

