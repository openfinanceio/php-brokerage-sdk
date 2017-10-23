<?php

class BrokerageClientTest extends \PHPUnit\Framework\TestCase {
    public function testInstantiates() {
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf("\\CFX\\SDK\\Brokerage\\Client", $cfx);
    }


    // Subclients

    public function testCanGetAssetsSubclient() {
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf('\\CFX\\SDK\\Brokerage\\AssetsClient', $cfx->assets);
    }

	public function testCanGetAssetIntentsSubclient() {
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf('\\CFX\\SDK\\Brokerage\\AssetIntentsClient', $cfx->assetIntents);
    }

    public function testCanGetOrdersSubclient() {
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf('\\CFX\\SDK\\Brokerage\\OrdersClient', $cfx->orders);
    }

    public function testCanGetOrderIntentsSubclient() {
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf('\\CFX\\SDK\\Brokerage\\OrderIntentsClient', $cfx->orderIntents);
    }

    public function testCanGetUsersSubclient() {
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf('\\CFX\\SDK\\Brokerage\\UsersClient', $cfx->users);
    }
}


