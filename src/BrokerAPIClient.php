<?php
namespace CFX;

class BrokerAPIClient extends BaseAPIClient implements BrokerAPIClientInterface {
    protected $cache = array();




    // SubAPIs
    // Note that sub-apis receive both Factory and BrokerAPIClient at instantiation.

    public function assets(array $props=[]) {
        return $this->factory->new('api-object', 'asset', $props);
    }
    public function bankAccounts(array $props=[]) {
        return $this->factory->new('api-object', 'bank-account', $props);
    }
    public function holdings(array $props=[]) {
        return $this->factory->new('api-object', 'holding', $props);
    }
    public function intents(array $props=[]) {
        return $this->factory->new('api-object', 'intent', $props);
    }
    public function legalEntities(array $props=[]) {
        return $this->factory->new('api-object', 'legal-entity', $props);
    }
    public function orders(array $props=[]) {
        return $this->factory->new('api-object', 'order', $props);
    }
    public function portfolios(array $props=[]) {
        return $this->factory->new('api-object', 'portfolio', $props);
    }
    public function transactions(array $props=[]) {
        return $this->factory->new('api-object', 'transaction', $props);
    }
    public function users(array $props=[]) {
        return $this->factory->new('api-object', 'user', $props);
    }







    public function getAuthUrl(string $scopes=null) {
        //TODO: implement OAuth considerations
    }

    public function handoff($intent=null) {
        if (is_string($intent)) $intentId = $intent;
        elseif ($intent instanceof \CFX\IntentInterface) $intentId = $intent->getKey();

        $url = $this->baseHandoffUri;
        if ($intentId) $url .= $intentId;
        $url .= '?referrer='.$this->apiKey;

        // do handoff
        header("Location: $url");
        die();
    }
}

