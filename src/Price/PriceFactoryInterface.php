<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Price;

use ValueObjects\Web\Url;

interface PriceFactoryInterface
{
    /**
     * @param $token
     * @return array
     */
    public function getPriceMatrix(Url $theatreUrl, $token);
}
