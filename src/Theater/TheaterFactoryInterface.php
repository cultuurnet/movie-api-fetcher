<?php

namespace CultuurNet\MovieApiFetcher\Theater;

interface TheaterFactoryInterface
{
    /**
     * @param $kinepolisTid
     * @return string
     */
    public function mapTheater($kinepolisTid);
}
