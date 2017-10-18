<?php

class ExchangeClientIntegrationTest extends \PHPUnit\Framework\TestCase {
    protected static $cnf;

    public static function setUpBeforeClass() {
        self::$cnf = new \CFX\SDK\Exchange\Test\Config(__DIR__.'/config.php', __DIR__.'/config.local.php');
    }

    public function testAssetsClientCanGetAllAssets() {
        $cfx = new \CFX\SDK\Exchange\Client(
            self::$cnf->getBaseExchangeUri(),
            self::$cnf->getExchangeApiKey(),
            self::$cnf->getExchangeApiKeySecret(),
            new \GuzzleHttp\Client(['defaults' => [ 'config' => [ 'curl' => [ CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => false,]],'exceptions' => false,]])
        );

        $assets = $cfx->assets->get();
        $this->assertTrue(count($assets) > 0, "Should have returned a full collection of asset objects");
        $this->assertInstanceOf("\\CFX\\AssetInterface", $assets[0]);
    }

    public function testAssetsClientCanGetAssetById() {
        $cfx = new \CFX\SDK\Exchange\Client(
            self::$cnf->getBaseExchangeUri(),
            self::$cnf->getExchangeApiKey(),
            self::$cnf->getExchangeApiKeySecret(),
            new \GuzzleHttp\Client(['defaults' => [ 'config' => [ 'curl' => [ CURLOPT_SSL_VERIFYHOST => 0, CURLOPT_SSL_VERIFYPEER => false,]],'exceptions' => false,]])
        );

        $asset = $cfx->assets->get('id=FR008');
        $this->assertInstanceOf("\\CFX\\AssetInterface", $asset);
        $this->assertEquals('FR008', $asset->getId());
    }
}


