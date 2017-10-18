<?php

class ExchangeClientTest extends \PHPUnit\Framework\TestCase {
    public function testInstantiates() {
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf("\\CFX\\SDK\\ClientInterface", $cfx);
    }


    // Subclients

    public function testCanGetAssetsSubclient() {
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf('\\CFX\\SDK\\SubclientInterface', $cfx->assets);
    }

	public function testCanGetAssetIntentsSubclient() {
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf('\\CFX\\SDK\\SubclientInterface', $cfx->assetIntents);
    }

    public function testCanGetOrdersSubclient() {
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf('\\CFX\\SDK\\SubclientInterface', $cfx->orders);
    }

    public function testCanGetOrderIntentsSubclient() {
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf('\\CFX\\SDK\\SubclientInterface', $cfx->orderIntents);
    }

    public function testCanGetUsersSubclient() {
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf('\\CFX\\SDK\\SubclientInterface', $cfx->users);
    }
}


