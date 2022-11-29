<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Fetcher;

use CultuurNet\MovieApiFetcher\Authentication\AuthenticationInterface;
use CultuurNet\MovieApiFetcher\Parser\ParserInterface;
use CultuurNet\MovieApiFetcher\Price\PriceFactoryInterface;
use CultuurNet\MovieApiFetcher\Url\UrlFactoryInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
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

    private bool $isDebug;

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
        Logger $logger,
        bool $isDebug
    ) {
        $this->client = $client;
        $this->secret = $secret;
        $this->authentication = $authentication;
        $this->urlFactory = $urlFactory;
        $this->parser = $parser;
        $this->priceFactory = $priceFactory;
        $this->logger = $logger;
        $this->isDebug = $isDebug;
    }

    public function start(): void
    {
        $token = $this->authentication->getToken($this->client, $this->secret, $this->isDebug);
        $body = $this->getMovies($token, $this->isDebug);

        $movies = $body['movies'];
        $this->logger->log(Logger::DEBUG, 'Found  ' . count($movies) . ' movies.');
        $theatreUrl = $this->urlFactory->generateTheatreUrl();
        $priceMatrix = $this->priceFactory->getPriceMatrix($theatreUrl, $token, $this->isDebug);
        foreach ($movies as $movie) {
            $mid = $movie['mid'];
            if ($mid !== 21285) {
                return;
            }
            $this->logger->log(Logger::DEBUG, 'Will parse movie  ' . $mid);
            $movieDetail = $this->getMovieDetail($token, $mid, $this->isDebug);
            try {
                $this->parser->process($movieDetail, $priceMatrix);
            } catch (\Exception $e) {
                var_dump($e->getTraceAsString());
                $this->logger->log(Logger::ERROR, 'Failed to Process movie ' . $e->getMessage());
            }
        }
        var_dump('sdf');
        $this->logger->log(Logger::DEBUG, 'Fetched all movies');
    }


    public function getMovies($token, bool $isDebug)
    {
        $client = new Client();
        $request = new Request(
            'GET',
            (string) $this->urlFactory->generateMoviesUrl(),
            [
                'content-type' => 'application/json',
                'Authorization' => $token,
                'User-Agent' => 'Kinepolis-Publiq',
            ]
        );

        $response = $client->send($request);

        $body  = $response->getBody()->getContents();

        return json_decode($body, true);
    }

    /**
     * @inheritdoc
     */
    public function getMovieDetail($token, $mid, bool $isDebug)
    {
        $client = new Client();
        $request = new Request(
            'GET',
            (string) $this->urlFactory->generateMovieDetailUrl($mid),
            [
                'content-type' => 'application/json',
                'Authorization' => $token,
                'User-Agent' => 'Kinepolis-Publiq',
            ]
        );

        $response = $client->send($request);

        $body  = $response->getBody()->getContents();

        return json_decode($body, true);
    }
}
