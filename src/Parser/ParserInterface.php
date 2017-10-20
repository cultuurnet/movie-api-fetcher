<?php

namespace CultuurNet\MovieApiFetcher\Parser;

interface ParserInterface
{
    /**
     * @param array[] $movies
     * @return string[]
     */
    public function split($movies);

    /**
     * @param array[] $movie
     * @return string[]
     */
    public function process($movie);
}
