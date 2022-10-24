<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Identification;

class IdentificationFactory implements IdentificationFactoryInterface
{
    /**
     * @inheritdoc
     */
    public function generateMovieProductionId($mid): string
    {
        return 'Kinepolis:' . 'm' . $mid;
    }

    /**
     * @inheritdoc
     */
    public function generateMovieId($mid, $tid, $version): string
    {
        $v = $version === '3D' ? 'v3D' : '';
        return 'Kinepolis:' . 't' . $tid . 'm' . $mid . $v;
    }
}
