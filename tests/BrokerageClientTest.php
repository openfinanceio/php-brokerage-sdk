<?php

class BrokerageClientTest extends \PHPUnit\Framework\TestCase {
    protected $cfx;

    public function setUp()
    {
        $this->cfx = new \CFX\SDK\Brokerage\Client('https://null.cfxtrading.com', '12345', 'abcde', new \GuzzleHttp\Client());
    }

    public function testInstantiates() {
        $this->assertInstanceOf("\\CFX\\SDK\\Brokerage\\Client", $this->cfx);
    }


    // Subclients

    public function testCanGetAssetsSubclient()
    {
        $client = $this->cfx->assets;
        $this->assertInstanceOf('\\CFX\\Persistence\\Rest\\GenericDatasource', $client);
        $this->assertInstanceOf("\\CFX\\Exchange\\Asset", $client->create());
        $this->assertEquals("assets", $client->getResourceType());
    }

    public function testCanGetAssetIntentsSubclient()
    {
        $client = $this->cfx->assetIntents;
        $this->assertInstanceOf('\\CFX\\Persistence\\Rest\\GenericDatasource', $client);
        $this->assertInstanceOf("\\CFX\\Brokerage\\AssetIntent", $client->create());
        $this->assertEquals("asset-intents", $client->getResourceType());
    }

    public function testCanGetOrdersSubclient()
    {
        $client = $this->cfx->orders;
        $this->assertInstanceOf('\\CFX\\Persistence\\Rest\\GenericDatasource', $client);
        $this->assertInstanceOf("\\CFX\\Exchange\\Order", $client->create());
        $this->assertEquals("orders", $client->getResourceType());
    }

    public function testCanGetOrderIntentsSubclient()
    {
        $client = $this->cfx->orderIntents;
        $this->assertInstanceOf('\\CFX\\Persistence\\Rest\\GenericDatasource', $client);
        $this->assertInstanceOf("\\CFX\\Brokerage\\OrderIntent", $client->create());
        $this->assertEquals("order-intents", $client->getResourceType());
    }

    public function testCanGetUsersSubclient()
    {
        $client = $this->cfx->users;
        $this->assertInstanceOf('\\CFX\\SDK\\Brokerage\\UsersDatasource', $client);
        $this->assertInstanceOf("\\CFX\\Brokerage\\User", $client->create());
        $this->assertEquals("users", $client->getResourceType());
    }

    public function testCanGetOauthTokensSubclient()
    {
        $client = $this->cfx->oauthTokens;
        $this->assertInstanceOf('\\CFX\\Persistence\\Rest\\GenericDatasource', $client);
        $this->assertInstanceOf("\\CFX\\Brokerage\\OAuthToken", $client->create());
        $this->assertEquals("oauth-tokens", $client->getResourceType());
    }

    public function testCanGetLegalEntitiesSubclient()
    {
        $client = $this->cfx->legalEntities;
        $this->assertInstanceOf('\\CFX\\Persistence\\Rest\\GenericDatasource', $client);
        $this->assertInstanceOf("\\CFX\\Brokerage\\LegalEntity", $client->create());
        $this->assertEquals("legal-entities", $client->getResourceType());
    }

    public function testCanGetAddressesSubclient()
    {
        $client = $this->cfx->addresses;
        $this->assertInstanceOf('\\CFX\\Persistence\\Rest\\GenericDatasource', $client);
        $this->assertInstanceOf("\\CFX\\Brokerage\\Address", $client->create());
        $this->assertEquals("addresses", $client->getResourceType());
    }

    public function testCanGetDocumentsSubclient()
    {
        $client = $this->cfx->documents;
        $this->assertInstanceOf('\\CFX\\Persistence\\Rest\\GenericDatasource', $client);
        $this->assertInstanceOf("\\CFX\\Brokerage\\Document", $client->create());
        $this->assertEquals("documents", $client->getResourceType());
    }

    public function testCanGetBankAccountsSubclient()
    {
        $client = $this->cfx->bankAccounts;
        $this->assertInstanceOf('\\CFX\\Persistence\\Rest\\GenericDatasource', $client);
        $this->assertInstanceOf("\\CFX\\Brokerage\\BankAccount", $client->create());
        $this->assertEquals("bank-accounts", $client->getResourceType());
    }

    public function testCanGetDocumentTemplatesSubclient()
    {
        $client = $this->cfx->documentTemplates;
        $this->assertInstanceOf('\\CFX\\Persistence\\Rest\\GenericDatasource', $client);
        $this->assertInstanceOf("\\CFX\\Brokerage\\DocumentTemplate", $client->create());
        $this->assertEquals("document-templates", $client->getResourceType());
    }

    public function testCanGetDealRoomsSubclient()
    {
        $client = $this->cfx->dealRooms;
        $this->assertInstanceOf('\\CFX\\Persistence\\Rest\\GenericDatasource', $client);
        $this->assertInstanceOf("\\CFX\\Brokerage\\DealRoom", $client->create());
        $this->assertEquals("deal-rooms", $client->getResourceType());
    }

    public function testCanGetTenderRoomsSubclient()
    {
        $client = $this->cfx->tenderRooms;
        $this->assertInstanceOf('\\CFX\\Persistence\\Rest\\GenericDatasource', $client);
        $this->assertInstanceOf("\\CFX\\Brokerage\\TenderRoom", $client->create());
        $this->assertEquals("tender-rooms", $client->getResourceType());
    }

    public function testCanGetTendersSubclient()
    {
        $client = $this->cfx->tenders;
        $this->assertInstanceOf('\\CFX\\Persistence\\Rest\\GenericDatasource', $client);
        $this->assertInstanceOf("\\CFX\\Brokerage\\Tender", $client->create());
        $this->assertEquals("tenders", $client->getResourceType());
    }
}


