<?php

namespace CultuurNet\MovieApiFetcher\Term;

interface TermFactoryInterface
{
    /**
     * @param $kinepolisTeid
     * @return string
     */
    public function mapTerm($kinepolisTeid);
}
