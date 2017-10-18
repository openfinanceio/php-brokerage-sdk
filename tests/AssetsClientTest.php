<?php

class AssetsClientTest extends \PHPUnit\Framework\TestCase {
    protected static $testAsset = [
        'attributes' =>[
        'issuer' => 'TEMP',
        'name' => '141 South Meridian Street',
        'statusCode' => '1',
        'statusText' => 'open',
        'description' => 'Test desc'
    ]];


    public function testAssetsClientComposesUriCorrectly() {
        $httpClient = new \CFX\Test\HttpClient();
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com/brokerage', '12345', 'abcde', $httpClient);

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode([self::$testAsset]))
        ));
        $assets = $cfx->assets->get();
        var_dump($assets);
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/assets', $r->getUrl());

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(self::$testAsset))
        ));
        $assets = $cfx->assets->get('id=FR008');
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/assets/FR008', $r->getUrl());
    }
}
