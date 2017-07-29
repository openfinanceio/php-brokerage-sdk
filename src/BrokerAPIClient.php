<?php
namespace CFX;

class BrokerAPIClient extends BaseAPIClient implements BrokerAPIClientInterface {
    protected $cache = array();

    public function users(array $opts=[], bool $async=false) {
        if (count($opts) == 0) return $this->factory->new('user', null);

        //TODO: Implement async calls

        $ep = $this->getEndpoint('users-get');
        $data = $this->restCall($ep[0], $ep[1], $opts);
    }







    // endpoints
    protected function getEndpoint(string $name, array $params=[]) {
        if ($name == 'users-get') $ep = [ 'GET', '/users' ];
        else return parent::getEndpoint($name, $params);

        if (count($params) > 0) $ep[1] = str_replace(array_keys($params), array_values($v), $ep[1]);
        return $ep;
    }
}

