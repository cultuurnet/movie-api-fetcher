<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores;

interface TypeRepositoryInterface
{
    public function getTypeId(
        string $externalId
    ): ?string;

    public function saveTypeId(
        string $externalId,
        string $typeId
    ): void;

    public function updateTypeId(
        string $externalId,
        string $typeId
    ): void;
}
