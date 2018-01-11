<?php
namespace CFX\SDK\Brokerage;

class GenericDatasourceTest extends \PHPUnit\Framework\TestCase {
    protected static $testAssetIntent = [
        'type' => 'asset-intents',
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
            'dateOpened' => 1234566666,
            'dateClosed' => 1234556677,
            'initialSharePrice' => '0',
            'holdingPeriod' => '0',
            'comments' => '',
        ]
    ];

    protected static $testAsset = [
        'type' => 'assets',
        'attributes' =>[
            'issuer' => 'TEMP',
            'name' => '141 South Meridian Street',
            'statusCode' => '1',
            'statusText' => 'open',
            'description' => 'Test desc',
        ],
    ];

    protected static $testOrderIntent = [
        'type' => 'order-intents',
        'attributes' => [
            'numShares' => '1',
            'priceHigh' => '12',
            'priceLow' => '11',
        ],
    ];
    
    protected static $testOrder = [
        'type' => 'orders',
        'attributes' => [
            'lotSize' => '1',
            'priceHigh' => '12',
            'priceLow' => '11',
            'side' => 'sell',
        ],
    ];
    
    protected static $testUser = [
        'type' => 'users',
        'attributes' => [
            'email' => 'q@q.com',
            'phoneNumber' => '999',
            'displayName' => 'Qusai',
            'timezone' => 'UM12',
            'language' => 'English',
        ],
    ];



    public function testGenericDatasourceSendsPartialDataForChanges()
    {
        $httpClient = new \CFX\Persistence\Test\HttpClient();
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', $httpClient);

        $testIntent = self::$testAssetIntent;
        $testIntent['id'] = '12345';
        $httpClient->setNextResponse(new \GuzzleHttp\Psr7\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Psr7\stream_for(json_encode(['data' => $testIntent]))
        ));

        $intent = $cfx->assetIntents->get('id=12345');

        $this->assertEquals(['type' => 'asset-intents', 'id' => '12345', 'attributes' => []], $intent->getChanges());

        $intent
            ->setDescription('new description')
            ->setExemptionType('Reg A+');

        $expectedChanges = [
            'id' => '12345',
            'type' => 'asset-intents',
            'attributes' => [
                'description' => 'new description',
                'exemptionType' => 'Reg A+',
            ]
        ];

        $this->assertEquals($expectedChanges, $intent->getChanges());

        $testIntent['attributes']['description'] = 'new description';
        $testIntent['attributes']['exemptionType'] = 'Reg A+';
        $httpClient->setNextResponse(new \GuzzleHttp\Psr7\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Psr7\stream_for(json_encode(['data' => $testIntent]))
        ));
        $intent->save();

        $r = $httpClient->getLastRequest();
        $data = json_decode($r->getBody(), true);
        $this->assertTrue(is_array($data));
        $this->assertContains('data', array_keys($data));
        $this->assertEquals($expectedChanges, $data['data']);
    }


    public function testAssetIntentsClientComposesUriCorrectly() {
        $httpClient = new \CFX\Persistence\Test\HttpClient();
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', $httpClient);

            
        $httpClient->setNextResponse(new \GuzzleHttp\Psr7\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Psr7\stream_for(json_encode(['data' => [self::$testAssetIntent]]))
        ));
        $assetIntents = $cfx->assetIntents->get();
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/asset-intents', $r->getUri());

        $httpClient->setNextResponse(new \GuzzleHttp\Psr7\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Psr7\stream_for(json_encode(['data' => self::$testAssetIntent]))
        ));
        $assetIntents = $cfx->assetIntents->get('id=FR008');
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/asset-intents/FR008', $r->getUri());
    }

    public function testAssetsClientComposesUriCorrectly() {
        $httpClient = new \CFX\Persistence\Test\HttpClient();
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', $httpClient);

        $httpClient->setNextResponse(new \GuzzleHttp\Psr7\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Psr7\stream_for(json_encode(['data' => [self::$testAsset]]))
        ));
        $assets = $cfx->assets->get();
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/assets', $r->getUri());

        $httpClient->setNextResponse(new \GuzzleHttp\Psr7\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Psr7\stream_for(json_encode(['data' => self::$testAsset]))
        ));
        $assets = $cfx->assets->get('id=FR008');
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/assets/FR008', $r->getUri());
    }

    public function testOrderIntentsClientComposesUriCorrectly() {
        $httpClient = new \CFX\Persistence\Test\HttpClient();
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', $httpClient);

        $httpClient->setNextResponse(new \GuzzleHttp\Psr7\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Psr7\stream_for(json_encode(['data' => [self::$testOrderIntent]]))
        ));

        $cfx->setOAuthToken('12345');
        $orderIntents = $cfx->orderIntents->get();
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/order-intents', $r->getUri());

        $httpClient->setNextResponse(new \GuzzleHttp\Psr7\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Psr7\stream_for(json_encode(['data' => self::$testOrderIntent]))
        ));
        $orderIntents = $cfx->orderIntents->get('id=OR008');
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/order-intents/OR008', $r->getUri());
    }

    public function testOrdersClientComposesUriCorrectly() {
        $httpClient = new \CFX\Persistence\Test\HttpClient();
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', $httpClient);

        $httpClient->setNextResponse(new \GuzzleHttp\Psr7\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Psr7\stream_for(json_encode(['data' => [self::$testOrder]]))
        ));

        $cfx->setOAuthToken('12345');

        $orders = $cfx->orders->get();
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/orders', $r->getUri());

        $httpClient->setNextResponse(new \GuzzleHttp\Psr7\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Psr7\stream_for(json_encode(['data' => self::$testOrder]))
        ));
        $orders = $cfx->orders->get('id=FR008');
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/orders/FR008', $r->getUri());
    }

    public function testUsersClientComposesUriCorrectly() {
        $httpClient = new \CFX\Persistence\Test\HttpClient();
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', $httpClient);

        $httpClient->setNextResponse(new \GuzzleHttp\Psr7\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Psr7\stream_for(json_encode(['data' => [self::$testUser]]))
        ));

        $cfx->setOAuthToken('12345');

        $users = $cfx->users->get();
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/users', $r->getUri());

        $httpClient->setNextResponse(new \GuzzleHttp\Psr7\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Psr7\stream_for(json_encode(['data' => self::$testUser]))
        ));
        $users = $cfx->users->get('id=FR008');
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/users/FR008', $r->getUri());
    }
}

