<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores;

use CultuurNet\TransformEntryStore\ValueObjects\TargetAudience\TargetAudience;

interface TargetAudienceInterface
{
    public function getTargetAudience(
        string $externalId
    );

    public function saveTargetAudience(
        string $externalId,
        TargetAudience $targetAudience
    ): void;
}
