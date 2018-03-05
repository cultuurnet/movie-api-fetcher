<?php

namespace CultuurNet\MovieApiFetcher\ProductionPoster;

use ValueObjects\StringLiteral\StringLiteral;

interface ProductionPosterInterface
{
    /**
     * @param StringLiteral $productionXml
     * @return bool
     */
    public function postProduction(StringLiteral $productionXml);
}
