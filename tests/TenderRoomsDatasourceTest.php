<?php
namespace CFX\SDK\Brokerage;

class TenderRoomsDatasourceTest extends AbstractDatasourceTest
{
    protected $datasourceName = 'tenderRooms';

    public function testGetRelated()
    {
        $this->httpClient->setNextResponse(new \GuzzleHttp\Message\Response(200, [], \GuzzleHttp\Stream\Stream::factory('{"data":[]}')));
        $this->datasource->getRelated('tenders', '12345');
        $r = $this->httpClient->getLastRequest();
        $this->assertContains('/tenders?q='.urlencode("tenderRoomId=12345"), $r->getUrl());
    }
}

