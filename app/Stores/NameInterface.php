<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores;

interface NameInterface
{
    public function getName(
        string $externalId
    ): ?string;

    public function saveName(
        string $externalId,
        string $name
    );

    public function updateName(
        string $externalId,
        string $name
    );
}
