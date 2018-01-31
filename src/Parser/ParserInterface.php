<?php

namespace CultuurNet\MovieApiFetcher\Parser;

interface ParserInterface
{
    /**
     * @param array[] $movie
     * @return string[]
     */
    public function process($movie, $priceMatrix);
}
