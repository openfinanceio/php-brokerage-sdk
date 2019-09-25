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
     * @var string A v1 OAuth token to use as authentication for requests that require it
     */
    protected $oAuthTokenV1;

    /**
     * @var string A v2 OAuth token to use as authentication for requests that require it
     */
    protected $oAuthTokenV2;

    /**
     * @var string A v2 ID token to use as authentication for requests that require it
     */
    protected $idToken;

    /**
     * @inheritdoc
     *
     * Note that most of the datasources returned here are simple REST `GenericDatasource`s. `UsersDatasource` is
     * the only exception, and this is to prevent the SDK from needlessly seeking OAuth token objects that it
     * won't have access to.
     */
    protected function instantiateDatasource($name) {
        if ($name === 'aclEntries') return new \CFX\Persistence\Rest\GenericDatasource($this, "acl-entries", "\\CFX\\Brokerage\\AclEntry");
        if ($name === 'assets') return new \CFX\Persistence\Rest\GenericDatasource($this, "assets", "\\CFX\\Exchange\\Asset");
        if ($name === 'assetIntents') return new \CFX\Persistence\Rest\GenericDatasource($this, "asset-intents", "\\CFX\\Brokerage\\AssetIntent");
        if ($name === 'orders') return new \CFX\Persistence\Rest\GenericDatasource($this, "orders", "\\CFX\\Exchange\\Order");
        if ($name === 'orderIntents') return new \CFX\Persistence\Rest\GenericDatasource($this, "order-intents", "\\CFX\\Brokerage\\OrderIntent");
        if ($name === 'users') return new UsersDatasource($this, "users", "\\CFX\\Brokerage\\User");
        if ($name === 'oauthTokens') return new \CFX\Persistence\Rest\GenericDatasource($this, "oauth-tokens", "\\CFX\\Brokerage\\OAuthToken");
        if ($name === 'legalEntities') return new LegalEntitiesDatasource($this, "legal-entities", "\\CFX\\Brokerage\\LegalEntity");
        if ($name === 'addresses') return new \CFX\Persistence\Rest\GenericDatasource($this, "addresses", "\\CFX\\Brokerage\\Address");
        if ($name === 'documents') return new \CFX\Persistence\Rest\GenericDatasource($this, "documents", "\\CFX\\Brokerage\\Document");
        if ($name === 'bankAccounts') return new \CFX\Persistence\Rest\GenericDatasource($this, "bank-accounts", "\\CFX\\Brokerage\\BankAccount");
        if ($name === 'documentTemplates') return new \CFX\Persistence\Rest\GenericDatasource($this, "document-templates", "\\CFX\\Brokerage\\DocumentTemplate");
        if ($name === 'dealRooms') return new \CFX\Persistence\Rest\GenericDatasource($this, "deal-rooms", "\\CFX\\Brokerage\\DealRoom");
        if ($name === 'tenderRooms') return new TenderRoomsDatasource($this, "tender-rooms", "\\CFX\\Brokerage\\TenderRoom");
        if ($name === 'tenders') return new \CFX\Persistence\Rest\GenericDatasource($this, "tenders", "\\CFX\\Brokerage\\Tender");

        return parent::instantiateDatasource($name);
    }

    /**
     * setOAuthToken -- Set a v1 OAuth token to be used for requests that require one
     *
     * NOTE: This is a v1 oauth token. For v2 oauth token, use `setV2OAuthToken`
     *
     * @param string|\CFX\Brokerage\OAuthTokenInterface|null $token the token to use
     */
    public function setOAuthToken($token = null) {
        if ($token instanceof \CFX\Brokerage\OAuthTokenInterface) {
            $token = $token->getId();
        }
        $this->oAuthTokenV1 = $token;
    }

    /**
     * setOAuthTokenV2 -- Set a v2 OAuth token to be used for requests that require one
     *
     * @param string|\CFX\Brokerage\OAuthTokenInterface|null $token the token to use
     */
    public function setOAuthTokenV2($token = null) {
        if ($token instanceof \CFX\Brokerage\OAuthTokenInterface) {
            $token = $token->getId();
        }
        $this->oAuthTokenV2 = $token;
    }

    /**
     * setIdToken -- Set a v2 ID Token
     *
     * @param string $token The token to use
     */
    public function setIdToken($token = null)
    {
        $this->idToken = $token;
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
            if ($this->oAuthTokenV1 || $this->oAuthTokenV2) {
                if (!array_key_exists('headers', $params)) $params['headers'] = [];

                // Add the Bearer Authorization header, if not already set
                $set = false;
                $authHeader = $params["headers"]["authorization"] ?? null;
                if ($authHeader) {
                    $authHeader = explode(",", $authHeader);
                    foreach($authHeader as $auth) {
                        if (strtolower(substr($auth, 0, strlen("bearer "))) === "bearer ") {
                            $set = true;
                        }
                    }
                } else {
                    $authHeader = [];
                }

                if (!$set) {
                    $authHeader[] = "Bearer ".($this->oAuthTokenV2 ?? $this->oAuthTokenV1);
                }

                $params['headers']['Authorization'] = implode(",", $authHeader);

                // Set the Auth version header
                if (!$this->getHeaderValue($params["headers"], "x-auth-version")) {
                    $params["headers"]["X-Auth-Version"] = $this->oAuthTokenV2 ? 2 : 1;
                }

                // Set the id token, if provided
                if ($this->idToken) {
                    $params["headers"]["X-ID-Token"] = $this->idToken;
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

    protected function getHeaderValue(array $headers, string $key)
    {
        foreach($headers as $k => $v) {
            if (strtolower($k) === strtolower($key)) {
                return $v;
            }
            return null;
        }
    }
}

