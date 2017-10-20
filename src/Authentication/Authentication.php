<?php

namespace CultuurNet\MovieApiFetcher\Authentication;

use CultuurNet\MovieApiFetcher\Url\UrlFactoryInterface;
use Guzzle\Http\Client;
use ValueObjects\StringLiteral\StringLiteral;

class Authentication implements AuthenticationInterface
{
    /**
     * @var UrlFactoryInterface
     */
    private $urlFactory;

    /**
     * @inheritdoc
     */
    public function getToken(StringLiteral $key, StringLiteral $secret)
    {
        $client = new Client();
        $uri = (string) $this->urlFactory->generateTokenUrl();
        $postBody = $this->generatePostBody($key, $secret);

        $request = $client->post(
            $uri,
            [
                'content-type' => 'application/json',
            ],
            []
        );

        $request->setBody($postBody);
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);

        $tokenBearer = 'Bearer ' . $resp['token'];

        return $tokenBearer;
    }


    public function __construct(UrlFactoryInterface $urlFactory)
    {
        $this->urlFactory = $urlFactory;
    }

    /**
     * @param StringLiteral $key
     * @param StringLiteral $secret
     * @return string
     */
    private function generatePostBody(StringLiteral $key, StringLiteral $secret)
    {
        return '{ "client": "' . $key->toNative() . '", "secret": "' . $secret->toNative() . '" }';
    }
}
