<?php
namespace CFX\SDK\Brokerage;

class Client extends \CFX\Persistence\Rest\AbstractDataContext {
    protected static $apiName = 'brokerage';
    protected static $apiVersion = '2';
    protected $oAuthToken;

    protected function instantiateDatasource($name) {
        if ($name == 'assets') return new \CFX\Persistence\Rest\GenericDatasource($this, $name, "\\CFX\\Exchange\\Asset");
        if ($name == 'assetIntents') return new \CFX\Persistence\Rest\GenericDatasource($this, $name, "\\CFX\\Brokerage\\AssetIntent");
        if ($name == 'orders') return new \CFX\Persistence\Rest\GenericDatasource($this, $name, "\\CFX\\Exchange\\Order");
        if ($name == 'orderIntents') return new \CFX\Persistence\Rest\GenericDatasource($this, $name, "\\CFX\\Brokerage\\OrderIntent");
        if ($name == 'users') return new UsersDatasource($this);
        if ($name == 'oauthTokens') return new \CFX\Persistence\Rest\GenericDatasource($this, $name, "\\CFX\\Brokerage\\OAuthToken");

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

    protected function requestRequiresOAuth($method, $endpoint, array $params = []) {
        return false;
    }
}

