<?php

namespace CultuurNet\MovieApiFetcher\Identification;

use ValueObjects\StringLiteral\StringLiteral;

class IdentificationFactory implements IdentificationFactoryInterface
{
    /**
     * @inheritdoc
     */
    public function generateMovieProductionId($mid)
    {
        return new StringLiteral('Kinepolis:' . 'm' . $mid);
    }

    /**
     * @inheritdoc
     */
    public function generateMovieId($mid, $tid)
    {
        return new StringLiteral('Kinepolis:' . 't' . $tid . 'm' . $mid);
    }
}
