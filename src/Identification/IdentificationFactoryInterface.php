<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Identification;

interface IdentificationFactoryInterface
{
    /**
     * @param $mid
     * @return string
     */
    public function generateMovieProductionId($mid);

    /**
     * @param $mid
     * @param $tid
     * @param $version
     * @return string
     */
    public function generateMovieId($mid, $tid, $version);
}
