<?php
namespace CFX\SDK\Brokerage;

abstract class AbstractDatasourceTest extends \PHPUnit\Framework\TestCase
{
    protected $httpClient;
    protected $cfx;
    protected $datasource;
    protected $datasourceName;

    public function setUp()
    {
        $ds = $this->datasourceName;

        if (!$ds) {
            throw new \RuntimeException("You must provide a datasource name via the `protected \$datasourceName` property");
        }

        $this->httpClient = new \CFX\Persistence\Test\HttpClient();
        $this->cfx = new Client("https://null.cfxtrading.com", "12345", "12345", $this->httpClient);
        $this->datasource = $this->cfx->$ds;
    }
}

