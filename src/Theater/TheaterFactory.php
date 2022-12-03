<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Theater;

use ValueObjects\Identity\UUID;

class TheaterFactory implements TheaterFactoryInterface
{
    /**
     * @var array
     */
    private $theaters;

    /**
     * TheaterFactory constructor.
     * @param $theaters
     */
    public function __construct($theaters)
    {
        $this->theaters = $theaters;
    }

    /**
     * @inheritdoc
     */
    public function mapTheater($kinepolisTid)
    {
        return new UUID($this->theaters['kinepolis_theaters'][$kinepolisTid]['cdbid']);
    }
}
