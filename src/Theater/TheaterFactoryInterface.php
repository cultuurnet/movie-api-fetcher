<?php

namespace CultuurNet\MovieApiFetcher\Theater;

use ValueObjects\Identity\UUID;

interface TheaterFactoryInterface
{
    /**
     * @param $kinepolisTid
     * @return UUID
     */
    public function mapTheater($kinepolisTid);
}
