<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Fetcher;

interface FetcherInterface
{
    public function start(): void;

    public function getMovies($token);

    public function getMovieDetail($token, $mid);
}
