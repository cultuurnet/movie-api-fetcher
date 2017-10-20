<?php

namespace CultuurNet\MovieApiFetcher\Fetcher;

use CultuurNet\MovieApiFetcher\Authentication\AuthenticationInterface;
use CultuurNet\MovieApiFetcher\Parser\ParserInterface;
use CultuurNet\MovieApiFetcher\Url\UrlFactoryInterface;
use Guzzle\Http\Client;
use ValueObjects\StringLiteral\StringLiteral;

class Fetcher implements FetcherInterface
{
    /**
     * @var StringLiteral
     */
    private $client;

    /**
     * @var StringLiteral
     */
    private $secret;

    /**
     * @var AuthenticationInterface
     */
    private $authentication;

    /**
     * @var UrlFactoryInterface
     */
    private $urlFactory;

    /**
     * @var ParserInterface
     */
    private $parser;

    /**
     * Fetcher constructor.
     * @param StringLiteral $client
     * @param StringLiteral $secret
     * @param AuthenticationInterface $authentication
     * @param UrlFactoryInterface $urlFactory
     * @param ParserInterface $parser
     */
    public function __construct(
        StringLiteral $client,
        StringLiteral $secret,
        AuthenticationInterface $authentication,
        UrlFactoryInterface $urlFactory,
        ParserInterface $parser
    ) {
        $this->client = $client;
        $this->secret = $secret;
        $this->authentication = $authentication;
        $this->urlFactory = $urlFactory;
        $this->parser = $parser;
    }

    /**
     * @return void
     */
    public function start()
    {
        $token = $this->authentication->getToken($this->client, $this->secret);
        $body = $this->getBody($token);
        var_dump($body);
        $this->parser->split($body['movies']);

    }

    public function getBody($token)
    {
        $client = new Client();
        $request = $client->get(
            (string) $this->urlFactory->generateMoviesUrl(),
            [
                'content-type' => 'application/json',
                'Authorization' => $token,
            ],
            []
        );

        $response = $request->send();

        $body  = $response->getBody();

        return json_decode($body, true);
    }
}
