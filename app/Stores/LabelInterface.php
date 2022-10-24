<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores;

interface LabelInterface
{
    public function addLabel(
        string $externalId,
        string $label
    ): void;

    public function deleteLabel(
        string $externalId,
        string $label
    ): void;
}
