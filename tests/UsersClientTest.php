<?php

class UsersClientTest extends \PHPUnit\Framework\TestCase {
    
    protected static $testUser = [
        'attributes' => [
        'email' => 'q@q.com',
        'phoneNumber' => '999',
        'displayName' => 'Qusai',
        'timezone' => 'UM12',
        'language' => 'English',
    ]];

    public function testUsersClientComposesUriCorrectly() {
        $httpClient = new \CFX\Test\HttpClient();
        $cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com/brokerage', '12345', 'abcde', $httpClient);

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode([self::$testUser]))
        ));
        $users = $cfx->users->get();
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/users', $r->getUrl());

        $httpClient->setNextResponse(new \GuzzleHttp\Message\Response(
            200,
            ['Content-Type' => 'application/json'],
            \GuzzleHttp\Stream\Stream::factory(json_encode(self::$testUser))
        ));
        $users = $cfx->users->get('id=US008');
        $r = $httpClient->getLastRequest();
        $this->assertEquals('https://null.cfxtrading.com/brokerage/v2/users/US008', $r->getUrl());
    }
}
