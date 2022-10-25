<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Term;

interface TermFactoryInterface
{
    /**
     * @param $kinepolisTeid
     * @return string
     */
    public function mapTerm($kinepolisTeid): ?string;
}
