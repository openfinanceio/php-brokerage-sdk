<?php
namespace CFX\SDK\Brokerage;

class GenericDatasourceTest extends \PHPUnit\Framework\TestCase {
    protected static $testAssetIntent = [
        'attributes' =>[
            'symbol' => 'FR008',
            'name' => 'testing intent',
            'description' => 'test desc',
            'assetType' => '',
            'financeType' => 'equity',
            'exemptionType' => '506c',
            'edgarNum' => '0',
            'cusipNum' => '0',
            'sharesOutstanding' => '0',
            'offerAmount' => '0',
            'dateOpened' => '2005-01-01 00:00:00',
            'dateClosed' => '2005-01-01 00:00:00',
            'initialSharePrice' => '0',
            'holdingPeriod' => '0',
            'comments' => '',
        ]
    ];

    protected static $testAsset = [
        'attributes' =>[
            'issuer' => 'TEMP',
            'name' => '141 South Meridian Street',
            'statusCode' => '1',
            'statusText' => 'open',
            'description' => 'Test desc',
        ],
    ];

    protected static $testOrderIntent = [
        'attributes' => [
            'numShares' => '1',
            'priceHigh' => '12',
            'priceLow' => '11',
        ],
    ];
    
    protected static $testOrder = [
        'attributes' => [
            'lotSize' => '1',
            'priceHigh' => '12',
            'priceLow' => '11',
            'side' => 'sell',
        ],
    ];
    
    protected static $testUser = [
        'attributes' => [
            'email' => 'q@q.com',
            'phoneNumber' => '999',
            'displayName' => 'Qusai',
            'timezone' => 'UM12',
            'language' => 'English',
        ],
    ];



    public function testAssetIntentsClientComposesUriCorrectly() {
        $httpClient = new \CFX\Persistence\Test\HttpClient();
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', $httpClient);

            
        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(['data' => [self::$testAssetIntent]]))
        ));
        $assetIntents = $cfx->assetIntents->get();
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/asset-intents', $r->getUrl());

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(['data' => self::$testAssetIntent]))
        ));
        $assetIntents = $cfx->assetIntents->get('id=FR008');
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/asset-intents/FR008', $r->getUrl());
    }

    public function testAssetsClientComposesUriCorrectly() {
        $httpClient = new \CFX\Persistence\Test\HttpClient();
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', $httpClient);

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(['data' => [self::$testAsset]]))
        ));
        $assets = $cfx->assets->get();
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/assets', $r->getUrl());

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(['data' => self::$testAsset]))
        ));
        $assets = $cfx->assets->get('id=FR008');
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/assets/FR008', $r->getUrl());
    }

    public function testOrderIntentsClientComposesUriCorrectly() {
        $httpClient = new \CFX\Persistence\Test\HttpClient();
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', $httpClient);

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(['data' => [self::$testOrderIntent]]))
        ));

        $cfx->setOAuthToken('12345');
        $orderIntents = $cfx->orderIntents->get();
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/order-intents', $r->getUrl());

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(['data' => self::$testOrderIntent]))
        ));
        $orderIntents = $cfx->orderIntents->get('id=OR008');
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/order-intents/OR008', $r->getUrl());
    }

    public function testOrdersClientComposesUriCorrectly() {
        $httpClient = new \CFX\Persistence\Test\HttpClient();
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', $httpClient);

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(['data' => [self::$testOrder]]))
        ));

        $cfx->setOAuthToken('12345');

        $orders = $cfx->orders->get();
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/orders', $r->getUrl());

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(['data' => self::$testOrder]))
        ));
        $orders = $cfx->orders->get('id=FR008');
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/orders/FR008', $r->getUrl());
    }

    public function testUsersClientComposesUriCorrectly() {
        $httpClient = new \CFX\Persistence\Test\HttpClient();
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', $httpClient);

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(['data' => [self::$testUser]]))
        ));

        $cfx->setOAuthToken('12345');

        $users = $cfx->users->get();
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/users', $r->getUrl());

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(['data' => self::$testUser]))
        ));
        $users = $cfx->users->get('id=FR008');
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/users/FR008', $r->getUrl());
    }
}

