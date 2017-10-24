<?php

namespace CultuurNet\MovieApiFetcher\Fetcher;

interface FetcherInterface
{
    /**
     * @return void
     */
    public function start();

    /**
     * @param $token
     * @return mixed
     */
    public function getMovies($token);

    /**
     * @param $token
     * @param $mid
     * @return mixed
     */
    public function getMovieDetail($token, $mid);
}
