<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores;

use ValueObjects\Identity\UUID;

interface ProductionInterface
{
    public function getProductionCdbid(
        string $externalId
    ): ?UUID;

    public function saveProductionCdbid(
        string $externalId,
        UUID $cdbid
    ): void;
}
