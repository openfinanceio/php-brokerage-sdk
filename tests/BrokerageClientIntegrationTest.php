<?php

class BrokerageClientIntegrationTest extends \PHPUnit\Framework\TestCase {
    protected static $cnf;
    protected static $factory;

    public static function setUpBeforeClass() {
        self::$cnf = new \CFX\SDK\Brokerage\Test\Config(__DIR__.'/config.php', __DIR__.'/config.local.php');
        self::$factory = new \CFX\Test\Factory();
    }



// AssetsClient

    public function testAssetsClientCanGetAllAssets() {
       
        $cfx = new \CFX\SDK\Brokerage\Client(
            self::$cnf->getBaseBrokerageUri(),
            self::$cnf->getBrokerageApiKey(),
            self::$cnf->getBrokerageApiKeySecret(),
            new \GuzzleHttp\Client(['defaults' => [ 'config' => [
                                                        'curl' => [ 
                                                                CURLOPT_SSL_VERIFYHOST => 0, 
                                                                CURLOPT_SSL_VERIFYPEER => false,
                                                            ]
                                                    ],
                                                    'exceptions' => false,
                                                    'headers' => [
                                                            'Content-Type' => 'application/vnd.api+json', 
                                                            'Accept' => 'application/vnd.api+json', 
                                                            'Authorization' => 'Basic'.base64_encode(self::$cnf->getBrokerageApiKey().":".self::$cnf->getBrokerageApiKeySecret())
                                                    ],
                                                ]
                                    ])
        );

        $assets = $cfx->assets->get();
        $this->assertTrue(count($assets) > 0, "Should have returned a full collection of asset objects");
        $this->assertInstanceOf("\\CFX\\AssetInterface", $assets[0]);
    }

    public function testAssetsClientCanGetAssetById() {
      
       $cfx = new \CFX\SDK\Brokerage\Client(
            self::$cnf->getBaseBrokerageUri(),
            self::$cnf->getBrokerageApiKey(),
            self::$cnf->getBrokerageApiKeySecret(),
            new \GuzzleHttp\Client(['defaults' => [ 'config' => [
                                                        'curl' => [ 
                                                                CURLOPT_SSL_VERIFYHOST => 0, 
                                                                CURLOPT_SSL_VERIFYPEER => false,
                                                            ]
                                                    ],
                                                    'exceptions' => false,
                                                    'headers' => [
                                                            'Content-Type' => 'application/vnd.api+json', 
                                                            'Accept' => 'application/vnd.api+json', 
                                                            'Authorization' => 'Basic'.base64_encode(self::$cnf->getBrokerageApiKey().":".self::$cnf->getBrokerageApiKeySecret())
                                                    ],
                                                ]
                                    ])
        );

        $asset = $cfx->assets->get('id=FR008');
        $this->assertInstanceOf("\\CFX\\AssetInterface", $asset);
        $this->assertEquals('FR008', $asset->getId());
    }




// OrdersClient

    



}



















