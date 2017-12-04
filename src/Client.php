<?php
namespace CFX\SDK\Brokerage;

/**
 * The Brokerage API Client
 *
 * This is a CFX REST DataContext derivative class, meaning that it serves datasources on request for various
 * types of resources and then ferries requests from those datasources off to the Brokreage API via an HTTP Client.
 */
class Client extends \CFX\Persistence\Rest\AbstractDataContext {
    /**
     * @var string The name of the API this client interfaces
     */
    protected static $apiName = 'brokerage';

    /**
     * @var string The version of the API this client interfaces
     */
    protected static $apiVersion = '2';

    /**
     * @var string An OAuth token to use as authentication for requests that require it
     */
    protected $oAuthToken;

    /**
     * @inheritdoc
     *
     * Note that most of the datasources returned here are simple REST `GenericDatasource`s. `UsersDatasource` is
     * the only exception, and this is to prevent the SDK from needlessly seeking OAuth token objects that it
     * won't have access to.
     */
    protected function instantiateDatasource($name) {
        if ($name === 'assets') return new \CFX\Persistence\Rest\GenericDatasource($this, "assets", "\\CFX\\Exchange\\Asset");
        if ($name === 'assetIntents') return new \CFX\Persistence\Rest\GenericDatasource($this, "asset-intents", "\\CFX\\Brokerage\\AssetIntent");
        if ($name === 'orders') return new \CFX\Persistence\Rest\GenericDatasource($this, "orders", "\\CFX\\Exchange\\Order");
        if ($name === 'orderIntents') return new \CFX\Persistence\Rest\GenericDatasource($this, "order-intents", "\\CFX\\Brokerage\\OrderIntent");
        if ($name === 'users') return new UsersDatasource($this);
        if ($name === 'oauthTokens') return new \CFX\Persistence\Rest\GenericDatasource($this, "oauth-tokens", "\\CFX\\Brokerage\\OAuthToken");
        if ($name === 'legalEntities') return new \CFX\Persistence\Rest\GenericDatasource($this, "legal-entities", "\\CFX\\Brokerage\\LegalEntity");
        if ($name === 'addresses') return new \CFX\Persistence\Rest\GenericDatasource($this, "addresses", "\\CFX\\Brokerage\\Address");
        if ($name === 'documents') return new \CFX\Persistence\Rest\GenericDatasource($this, "documents", "\\CFX\\Brokerage\\Document");
        if ($name === 'bankAccounts') return new \CFX\Persistence\Rest\GenericDatasource($this, "bank-accounts", "\\CFX\\Brokerage\\BankAccount");
        if ($name === 'documentTemplates') return new \CFX\Persistence\Rest\GenericDatasource($this, "document-templates", "\\CFX\\Brokerage\\DocumentTemplate");
        if ($name === 'dealRooms') return new \CFX\Persistence\Rest\GenericDatasource($this, "deal-rooms", "\\CFX\\Brokerage\\DealRoom");
        if ($name === 'tenderRooms') return new \CFX\Persistence\Rest\GenericDatasource($this, "tender-rooms", "\\CFX\\Brokerage\\TenderRoom");
        if ($name === 'tenders') return new \CFX\Persistence\Rest\GenericDatasource($this, "tenders", "\\CFX\\Brokerage\\Tender");

        return parent::instantiateDatasource($name);
    }

    /**
     * setOAuthToken -- Set an OAuth token to be used for requests that require one
     *
     * @param string|\CFX\Brokerage\OAuthTokenInterface|null $token the token to use
     */
    public function setOAuthToken($token = null) {
        if ($token instanceof \CFX\Brokerage\OAuthTokenInterface) {
            $token = $token->getId();
        }
        $this->oAuthToken = $token;
    }

    /**
     * Send a request to the Brokerage REST API
     *
     * Note that this method is responsible for knowing which endpoints require OAuth authorization and for
     * providing that authorization.
     * 
     * {@inheritdoc}
     */
    public function sendRequest($method, $endpoint, array $params = []) {
        if ($this->requestRequiresOAuth($method, $endpoint, $params)) {
            if ($this->oAuthToken) {
                if (!array_key_exists('headers', $params)) $params['headers'] = [];

                $set = false;
                for($i = 0, $keys = array_keys($params['headers']), $ln = count($keys); $i < $ln; $i++) {
                    if (strtolower($keys[$i]) === 'authorization') {
                        $set = true;
                        break;
                    }
                }

                if (!$set) {
                    $params['headers']['Authorization'] = "Bearer $this->oAuthToken";
                }
            } else {
                throw new \RuntimeException(
                    "Programmer: You need to provide an OAuth token for requests to `$method $endpoint`. ".
                    "You can do so using the `setOAuthToken` method of the Client object."
                );
            }
        }

        return parent::sendRequest($method, $endpoint, $params);
    }

    /**
     * Determines whether the given request requires an OAuth token for authorization
     *
     * @param string $method The HTTP method of the request
     * @param string $endpoint The endpoint for the request
     * @param array $params An optional hash of request parameters formatted for a Guzzle HTTP Client request
     */
    protected function requestRequiresOAuth($method, $endpoint, array $params = []) {
        $oauthEndpoints = [
            "/order-intents",
            "/orders",
            "/legal-entities",
            "/addresses",
            "/documents",
            "/bank-accounts",
        ];

        foreach($oauthEndpoints as $e) {
            if (substr($endpoint, 0, strlen($e)) === $e) {
                return true;
            }
        }

        $e = "/users";
        if (substr($endpoint, 0, strlen($e)) === $e && in_array($method, ["GET", "PATCH"])) {
            return true;
        }

        return false;
    }
}

