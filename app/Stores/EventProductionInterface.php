<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores;

use ValueObjects\Identity\UUID;

interface EventProductionInterface
{
    public function getCdbids(
        string $externalId
    ): ?array;

    public function saveEventProduction(
        string $externalIdEvent,
        string $externalIdProduction,
        UUID $cdbid
    ): void;
}
