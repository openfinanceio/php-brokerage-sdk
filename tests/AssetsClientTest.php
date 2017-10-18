<?php

class AssetsClientTest extends \PHPUnit\Framework\TestCase {
    protected static $testAsset = [
        "asset_id" => "36",
        "issuer_ident" => "TEMP",
        "account_key" => "76b7137d-5555-11e4-8141-003048d9078a",
        "asset_symbol" => "FR008",
        "asset_type" => "realestate",
        "offer_type" => "exchange",
        "finance_type" => "equity",
        "exemption_type" => "506c",
        "asset_name" => "141 South Meridian Street",
        "asset_description" => "Test Description",
        "offer_amount" => "250000",
        "max_amount" => "250000",
        "min_amount" => "250000",
        "share_price_initial" => "5000",
        "open_date" => "2005-01-01 00:00:00",
        "close_date" => "2005-01-01 00:00:00",
        "asset_status" => "1",
        "asset_status_text" => "open",
        "amount_reserved" => "0",
        "amount_investors" => "0",
    ];


    public function testAssetsClientComposesUriCorrectly() {
        $httpClient = new \CFX\Test\HttpClient();
        $cfx = new \CFX\SDK\Exchange\Client('https://null.cfxtrading.com', '12345', 'abcde', $httpClient);

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode([self::$testAsset]))
        ));
        $assets = $cfx->assets->get();
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/v0/assets', $r->getUrl());

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(self::$testAsset))
        ));
        $assets = $cfx->assets->get('id=FR008');
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/v0/assets?symbol=FR008', $r->getUrl());
    }
}
