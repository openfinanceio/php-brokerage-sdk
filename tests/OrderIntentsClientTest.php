<?php

class OrderIntentsClientTest extends \PHPUnit\Framework\TestCase {
    
    protected static $testOrderIntent = [
        'attributes' => [
            'numShares' => '1',
            'priceHigh' => '12',
            'priceLow' => '11',
        ],
    ];

    public function testOrderIntentsClientComposesUriCorrectly() {
        $httpClient = new \CFX\Persistence\Test\HttpClient();
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', $httpClient);

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(['data' => [self::$testOrderIntent]]))
        ));
        $orderIntents = $cfx->orderIntents->get();
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/orderIntents', $r->getUrl());

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(['data' => self::$testOrderIntent]))
        ));
        $orderIntents = $cfx->orderIntents->get('id=OR008');
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/orderIntents/OR008', $r->getUrl());
    }
}
