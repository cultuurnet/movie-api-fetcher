<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Price;

use ValueObjects\Web\Url;

interface PriceFactoryInterface
{
    public function getPriceMatrix(Url $theatreUrl, $token, bool $isDebug): array;
}
