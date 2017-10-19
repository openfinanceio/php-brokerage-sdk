<?php
namespace CFX\SDK\Brokerage\Test;

class Config extends \CFX\Config {
    public function getBaseBrokerageUri() { return $this->get('brokerage-base-uri'); }
    public function getBrokerageApiKey() { return $this->get('brokerage-api-key'); }
    public function getBrokerageApiKeySecret() { return $this->get('brokerage-api-key-secret'); }
}

