<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Fetcher;

use CultuurNet\MovieApiFetcher\Authentication\AuthenticationInterface;
use CultuurNet\MovieApiFetcher\Parser\ParserInterface;
use CultuurNet\MovieApiFetcher\Price\PriceFactoryInterface;
use CultuurNet\MovieApiFetcher\Url\UrlFactoryInterface;
use Guzzle\Http\Client;
use Monolog\Logger;

class Fetcher implements FetcherInterface
{
    private string $client;

    private string $secret;

    private AuthenticationInterface $authentication;

    private UrlFactoryInterface $urlFactory;

    private ParserInterface $parser;

    private PriceFactoryInterface $priceFactory;

    private Logger $logger;

    /**
     * Fetcher constructor.
     */
    public function __construct(
        string $client,
        string $secret,
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

    public function start(): void
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


    public function getMovies($token)
    {
        $client = new Client();
        $request = $client->get(
            (string) $this->urlFactory->generateMoviesUrl(),
            [
                'content-type' => 'application/json',
                'Authorization' => $token,
                'User-Agent' => 'Kinepolis-Publiq',
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
                'User-Agent' => 'Kinepolis-Publiq',
            ],
            []
        );

        $response = $request->send();

        $body  = $response->getBody();

        return json_decode($body, true);
    }
}
