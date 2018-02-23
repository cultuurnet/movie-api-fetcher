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
    public function generateMovieId($mid, $tid, $version)
    {
        $v = $version === '3D' ? 'v3D' : '';
        return new StringLiteral('Kinepolis:' . 't' . $tid . 'm' . $mid . $v);
    }
}
