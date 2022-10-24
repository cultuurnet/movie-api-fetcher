<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores;

interface DescriptionRepositoryInterface
{
    public function getDescription(
        string $externalId
    ): ?string;

    public function saveDescription(
        string $externalId,
        string $description
    ): void;

    public function updateDescription(
        string $externalId,
        string $description
    ): void;
}
