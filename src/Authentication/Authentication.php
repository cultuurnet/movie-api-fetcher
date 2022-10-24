<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Authentication;

use CultuurNet\MovieApiFetcher\Url\UrlFactoryInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class Authentication implements AuthenticationInterface
{
    private UrlFactoryInterface $urlFactory;

    public function getToken(string $key, string $secret): string
    {
        $client = new Client();
        $uri = (string) $this->urlFactory->generateTokenUrl();
        $postBody = $this->generatePostBody($key, $secret);

        $request = new Request(
            'POST',
            $uri,
            [
                'content-type' => 'application/json',
            ],
            $postBody
        );

        $response = $client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);

        return 'Bearer ' . $resp['token'];
    }


    public function __construct(UrlFactoryInterface $urlFactory)
    {
        $this->urlFactory = $urlFactory;
    }

    private function generatePostBody(string $key, string $secret): string
    {
        return '{ "client": "' . $key . '", "secret": "' . $secret . '" }';
    }
}
