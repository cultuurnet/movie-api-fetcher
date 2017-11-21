<?php

namespace CultuurNet\MovieApiFetcher\Identification;

use ValueObjects\StringLiteral\StringLiteral;

interface IdentificationFactoryInterface
{
    /**
     * @param $mid
     * @return StringLiteral
     */
    public function generateMovieProductionId($mid);

    /**
     * @param $mid
     * @param $tid
     * @return StringLiteral
     */
    public function generateMovieId($mid, $tid);
}
