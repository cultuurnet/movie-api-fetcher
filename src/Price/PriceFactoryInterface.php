<?php

namespace CultuurNet\MovieApiFetcher\Price;

use ValueObjects\Web\Url;

interface PriceFactoryInterface
{

    /**
     * @param Url $theatreUrl
     * @param $token
     * @return array
     */
    public function getPriceMatrix(Url $theatreUrl, $token);
}
