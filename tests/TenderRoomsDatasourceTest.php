<?php
namespace CFX\SDK\Brokerage;

class TenderRoomsDatasourceTest extends AbstractDatasourceTest
{
    protected $datasourceName = 'tenderRooms';

    public function testGetRelated()
    {
        $this->httpClient->setNextResponse(new \GuzzleHttp\Psr7\Response(200, [], \GuzzleHttp\Psr7\stream_for('{"data":[]}')));
        $this->datasource->getRelated('tenders', '12345');
        $r = $this->httpClient->getLastRequest();
        $this->assertContains('/tenders?q='.urlencode("tenderRoomId=12345"), (string)$r->getUri());
    }
}

