<?php

class BrokerageClientTest extends \PHPUnit\Framework\TestCase {
    public function testInstantiates() {
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf("\\CFX\\SDK\\Brokerage\\Client", $cfx);
    }


    // Subclients

    public function testCanGetAssetsSubclient() {
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf('\\CFX\\Persistence\\Rest\\GenericDatasource', $cfx->assets);
    }

	public function testCanGetAssetIntentsSubclient() {
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf('\\CFX\\Persistence\\Rest\\GenericDatasource', $cfx->assetIntents);
    }

    public function testCanGetOrdersSubclient() {
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf('\\CFX\\Persistence\\Rest\\GenericDatasource', $cfx->orders);
    }

    public function testCanGetOrderIntentsSubclient() {
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf('\\CFX\\Persistence\\Rest\\GenericDatasource', $cfx->orderIntents);
    }

    public function testCanGetUsersSubclient() {
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf('\\CFX\\SDK\\Brokerage\\UsersDatasource', $cfx->users);
    }
}


