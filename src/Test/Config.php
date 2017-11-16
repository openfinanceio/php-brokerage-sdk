<?php
namespace CFX\SDK\Brokerage\Test;

class Config extends \KS\BaseConfig {
    public function getBaseBrokerageUri() { return $this->get('brokerage-base-uri'); }
    public function getBrokerageApiKey() { return $this->get('brokerage-api-key'); }
    public function getBrokerageApiKeySecret() { return $this->get('brokerage-api-key-secret'); }
}

