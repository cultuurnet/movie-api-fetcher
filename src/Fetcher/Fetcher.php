<?php

namespace CultuurNet\MovieApiFetcher\Fetcher;

use CultuurNet\MovieApiFetcher\Authentication\AuthenticationInterface;
use CultuurNet\MovieApiFetcher\Parser\ParserInterface;
use CultuurNet\MovieApiFetcher\Url\UrlFactoryInterface;
use Guzzle\Http\Client;
use Monolog\Logger;
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
     * @var Logger
     */
    private $logger;

    /**
     * Fetcher constructor.
     * @param StringLiteral $client
     * @param StringLiteral $secret
     * @param AuthenticationInterface $authentication
     * @param UrlFactoryInterface $urlFactory
     * @param ParserInterface $parser
     * @param Logger $logger
     */
    public function __construct(
        StringLiteral $client,
        StringLiteral $secret,
        AuthenticationInterface $authentication,
        UrlFactoryInterface $urlFactory,
        ParserInterface $parser,
        Logger $logger
    ) {
        $this->client = $client;
        $this->secret = $secret;
        $this->authentication = $authentication;
        $this->urlFactory = $urlFactory;
        $this->parser = $parser;
        $this->logger = $logger;
    }

    /**
     * @return void
     */
    public function start()
    {
        $token = $this->authentication->getToken($this->client, $this->secret);
        $body = $this->getMovies($token);

        $movies = $body['movies'];
        foreach ($movies as $movie) {
            $mid = $movie['mid'];
            $movieDetail = $this->getMovieDetail($token, $mid);
            try {
                $this->parser->process($movieDetail);
            } catch (\Exception $e) {
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getMovies($token)
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

    /**
     * @inheritdoc
     */
    public function getMovieDetail($token, $mid)
    {
        $client = new Client();
        $request = $client->get(
            (string) $this->urlFactory->generateMovieDetailUrl($mid),
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
