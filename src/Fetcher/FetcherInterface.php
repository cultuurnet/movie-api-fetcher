<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Fetcher;

interface FetcherInterface
{
    public function start(): void;

    public function getMovies($token, bool $isDebug);

    public function getMovieDetail($token, $mid, bool $isDebug);
}
