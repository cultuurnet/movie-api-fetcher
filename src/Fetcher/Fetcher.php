<?php

namespace CultuurNet\MovieApiFetcher\Fetcher;

use CultuurNet\MovieApiFetcher\Authentication\AuthenticationInterface;
use CultuurNet\MovieApiFetcher\Parser\ParserInterface;
use CultuurNet\MovieApiFetcher\Price\PriceFactoryInterface;
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
     * @var PriceFactoryInterface
     */
    private $priceFactory;

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
     * @param PriceFactoryInterface $priceFactory
     * @param Logger $logger
     */
    public function __construct(
        StringLiteral $client,
        StringLiteral $secret,
        AuthenticationInterface $authentication,
        UrlFactoryInterface $urlFactory,
        ParserInterface $parser,
        PriceFactoryInterface $priceFactory,
        Logger $logger
    ) {
        $this->client = $client;
        $this->secret = $secret;
        $this->authentication = $authentication;
        $this->urlFactory = $urlFactory;
        $this->parser = $parser;
        $this->priceFactory = $priceFactory;
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
        $this->logger->log(Logger::DEBUG, 'Found  ' . count($movies) . ' movies.');
        $theatreUrl = $this->urlFactory->generateTheatreUrl();
        $priceMatrix = $this->priceFactory->getPriceMatrix($theatreUrl, $token);
        foreach ($movies as $movie) {
            $mid = $movie['mid'];
            $this->logger->log(Logger::DEBUG, 'Will parse movie  ' . $mid);
            $movieDetail = $this->getMovieDetail($token, $mid);
            try {
                $this->parser->process($movieDetail, $priceMatrix);
            } catch (\Exception $e) {
                $this->logger->log(Logger::ERROR, 'Failed to Process movie ' . $e->getMessage());
            }
        }
        $this->logger->log(Logger::DEBUG, 'Fetched all movies');
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
