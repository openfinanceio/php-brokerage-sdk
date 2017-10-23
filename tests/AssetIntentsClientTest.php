<?php

class AssetIntentsClientTest extends \PHPUnit\Framework\TestCase {
    
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
        ]];

    public function testAssetIntentsClientComposesUriCorrectly() {
        $httpClient = new \CFX\Persistence\Test\HttpClient();
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', $httpClient);

            
        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(self::$testAssetIntent))
        ));
        // $assetIntents = $cfx->assetIntents->get();
        // $r = $httpClient->getLastRequest();
        // $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/assetIntents', $r->getUrl());

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(['data' => self::$testAssetIntent]))
        ));
        $assetIntents = $cfx->assetIntents->get('id=FR008');
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/assetIntents/FR008', $r->getUrl());
    }
}
