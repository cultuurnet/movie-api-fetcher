<?php

namespace CultuurNet\MovieApiFetcher\ProductionPoster;

use Guzzle\Http\Client;
use Monolog\Logger;
use ValueObjects\StringLiteral\StringLiteral;
use ValueObjects\Web\Url;

class ProductionPoster implements ProductionPosterInterface
{
    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var array
     */
    private $sapi2Urls;

    /**
     * @inheritdoc
     */
    public function postProduction(StringLiteral $productionXml)
    {
        foreach ($this->sapi2Urls as $sapi2Url) {
            $client = new Client();
            $uri = (string) $sapi2Url;

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
        }

        return true;
    }

    public function __construct(Logger $logger, array $sapi2Urls)
    {
        $this->logger = $logger;
        $this->sapi2Urls = $sapi2Urls;
    }
}
