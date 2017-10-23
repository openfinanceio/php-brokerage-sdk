<?php

class OrdersClientTest extends \PHPUnit\Framework\TestCase {
    
    protected static $testOrder = [
        'attributes' => [
            'numShares' => '1',
            'priceHigh' => '12',
            'priceLow' => '11',
            'type' => 'sell',
        ],
    ];

    public function testOrderIntentsClientComposesUriCorrectly() {
        $httpClient = new \CFX\Persistence\Test\HttpClient();
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', $httpClient);

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(['data' => [self::$testOrder]]))
        ));
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
}
