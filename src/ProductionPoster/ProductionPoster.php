<?php

namespace CultuurNet\MovieApiFetcher\ProductionPoster;

use Guzzle\Http\Client;
use Monolog\Logger;
use ValueObjects\Identity\UUID;
use ValueObjects\StringLiteral\StringLiteral;
use ValueObjects\Web\Url;

class ProductionPoster implements ProductionPosterInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var Url
     */
    private $sapi2Url;

    /**
     * @inheritdoc
     */
    public function postProduction(StringLiteral $productionXml)
    {
        $productionXml = new StringLiteral('<?xml version="1.0" encoding="utf-8"?><cdbxml xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="http://www.cultuurdatabank.com/XMLSchema/CdbXSD/3.3/FINAL">  <production availablefrom="2018-02-28T11:57:10" availableto="2048-02-28T11:57:10" cdbid="42647ed5-6722-4806-8846-f947b870edf9" createdby="jonas@cultuurnet.be" creationdate="2018-02-28T11:57:09" externalid="Kinepolis_m41932" lastupdated="2018-02-28T11:57:09" lastupdatedby="jonas@cultuurnet.be" owner="CultuurNet Validatoren" publisher="48fe254ceba710aec4609017d2e34d91">    <categories>      <category catid="0.50.6.0.0" type="eventtype">Film</category>    </categories>    <productiondetails>      <productiondetail lang="nl">        <shortdescription>DDT is "back in business" en slaagt er op sluwe wijze in om eigenaar te worden van F.C. De Kampioenen. Boma wordt meteen buitenspelgezet en onze Kampioenen worden vervangen door een nieuw team van jonge, getalenteerde spelers...</shortdescription>        <title>F.C. De Kampioenen 3 - Forever</title>      </productiondetail>    </productiondetails>    <relatedevents><id cdbid="b2cd4536-702d-4dda-a0f2-ef5aa300bad6"/><id cdbid="d7fb0901-2a88-4b7d-83fc-6e24fa4f0ca5"/><id cdbid="488d3a2a-66a5-467e-8a0d-9737f48b0f37"/><id cdbid="259145a8-62a7-4ee9-a9db-afeaa14fa714"/><id cdbid="d2efa2f9-8273-4a7c-bbd8-c50c645c5071"/></relatedevents>  </production></cdbxml>');
        $client = new Client();
        $uri = (string) $this->sapi2Url;

        $request = $client->post(
            $uri,
            [
                'Content-Type' => 'application/xml',
            ],
            []
        );

        $request->setBody($productionXml->toNative());

        try {
            $response = $request->send();
        } catch (\Exception $e) {
            $this->logger->log(Logger::ERROR, 'Failed to post movie, message:  ' . $e->getMessage());
            return null;
        }

        $bodyResponse = $response->getBody();

        $resp = utf8_encode($bodyResponse);

        $this->logger->log(Logger::DEBUG, 'Posted production ' . $productionXml);
        $this->logger->log(Logger::DEBUG, $productionXml->toNative());

        return true;
    }

    public function __construct(Logger $logger, Url $sapi2Url)
    {
        $this->logger = $logger;
        $this->sapi2Url = $sapi2Url;
    }
}
