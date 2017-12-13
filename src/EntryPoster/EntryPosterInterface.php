<?php

namespace CultuurNet\MovieApiFetcher\EntryPoster;

use ValueObjects\Identity\UUID;
use ValueObjects\StringLiteral\StringLiteral;

interface EntryPosterInterface
{
    /**
     * @param StringLiteral $jsonMovie
     * @return UUID
     */
    public function postMovie(StringLiteral $jsonMovie);
}
