<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores;

use CultuurNet\TransformEntryStore\ValueObjects\AgeRange\AgeRange;
use ValueObjects\Identity\UUID;

interface AgeRangeInterface
{
    public function getAgeRange(string $externalId): ?UUID;

    public function saveAgeRange(string $externalId, AgeRange $ageRange): void;

    public function updateAgeRange(string $externalId, AgeRange $ageRange): void;
}
