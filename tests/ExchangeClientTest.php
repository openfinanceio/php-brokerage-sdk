<?php

class ExchangeClientTest extends \PHPUnit\Framework\TestCase {
    public function testInstantiates() {
        $cfx = new \CFX\SDK\Exchange\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf("\\CFX\\SDK\\ClientInterface", $cfx);
    }


    // Subclients

    public function testCanGetAssetsSubclient() {
        $cfx = new \CFX\SDK\Exchange\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
        $this->assertInstanceOf('\\CFX\\SDK\\SubclientInterface', $cfx->assets);
    }
}


