<?php

namespace CultuurNet\MovieApiFetcher\Fetcher;

interface FetcherInterface
{
    /**
     * @return void
     */
    public function start();

    public function getBody($token);
}
