<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores;

use ValueObjects\Identity\UUID;

interface LocationInterface
{
    public function getLocationCdbid(
        string $externalId
    ): ?string;

    public function saveLocationCdbid(
        string $externalId,
        UUID $locationCdbid
    );

    public function updateLocationCdbid(
        string $externalId,
        UUID $locationCdbid
    ): void;
}
