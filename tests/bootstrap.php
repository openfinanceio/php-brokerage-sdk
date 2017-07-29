<?php

require __DIR__.'/../vendor/autoload.php';

class TestFactory extends \CFX\Factory {
}

class BrokerAPIClientSpy extends \CFX\BrokerAPIClient {
    public function getApiKey() { return $this->apiKey; }
    public function getApiSecret() { return $this->apiSecret; }
    public function getBaseUri() { return $this->baseUri; }
    public function getFactory() { return $this->factory; }
}

