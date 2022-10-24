<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Parser;

interface ParserInterface
{
    /**
     * @param array[] $movie
     */
    public function process($movie, $priceMatrix): void;
}
