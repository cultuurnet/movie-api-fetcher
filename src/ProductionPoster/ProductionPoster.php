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
        $junk = array(
            '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>',
            '<importResult>',
            '<productionsImported>',
            '</productionsImported>',
            '</importResult>',
            ' ',
            PHP_EOL,
        );

        $productionCdid = str_replace($junk, '', $resp);

        $this->logger->log(Logger::DEBUG, 'Posted production ' . $productionCdid);

        return true;
    }

    public function __construct(Logger $logger, Url $sapi2Url)
    {
        $this->logger = $logger;
        $this->sapi2Url = $sapi2Url;
    }
}
