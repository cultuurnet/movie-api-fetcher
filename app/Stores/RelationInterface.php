<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores;

use ValueObjects\Identity\UUID;

interface RelationInterface
{
    public function getCdbid(
        string $externalId
    ): ?UUID;

    public function getExternalId(
        UUID $cdbid
    ): ?string;

    public function saveCdbid(
        string $externalId,
        UUID $cdbid
    ): void;
}
