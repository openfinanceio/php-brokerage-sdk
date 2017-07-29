<?php

class BrokerAPIClientTests extends PHPUnit\Framework\TestCase {
    public function testCanInstantiateAndAcceptsFactory() {
        $client = new \CFX\BrokerAPIClient('123', 'abcd');
        $this->assertTrue($client instanceof \CFX\BrokerAPIClientInterface);
        try {
            $client->setFactory(TestFactory::getInstance());
            $this->assertTrue(true, "Correct: no error thrown.");
        } catch (Exception $e) {
            $this->fail("Shouldn't have thrown an exception when trying to set factory");
        }
    }

    public function testRequiresProperInitialParameters() {
        // No arguments
        try {
            new \CFX\BrokerAPIClient();
        } catch (ArgumentCountError $e) {
            $this->assertTrue(true, 'Correctly threw an argument count error on too few initial arguments');
        }

        // Just one argument
        try {
            new \CFX\BrokerAPIClient('123');
        } catch (ArgumentCountError $e) {
            $this->assertTrue(true, 'Correctly threw an argument count error on too few initial arguments');
        }
    }

    public function testHandlesConstructorInputCorrectly() {
        $client = $this->getTestClient('123', 'abcd', [ 'baseUri' => 'https://test.com' ]);
        $this->assertTrue($client instanceof \CFX\BrokerAPIClientInterface);
        $this->assertEquals('https://test.com', $client->getBaseUri());
        $this->assertEquals('123', $client->getApiKey());
        $this->assertEquals('abcd', $client->getApiSecret());
    }

    public function testCanGetBlankUserObject() {
        $client = $this->getTestClient();
        $blankUser = $client->users();
        $this->assertTrue($blankUser instanceof \CFX\UserInterface, "Should have sent back a blank instance of UserInterface");
    }









    protected function getTestClient(string $apikey='123', string $secret='abcd', array $opts=[]) {
        $client = new BrokerAPIClientSpy($apikey, $secret, $opts);
        $client->setFactory(TestFactory::getInstance());
        return $client;
    }
}

