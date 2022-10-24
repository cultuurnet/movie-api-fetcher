<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Stores\TargetAudienceInterface;

use CultuurNet\TransformEntryStore\ValueObjects\TargetAudience\TargetAudience;

class StoreTargetAudienceDBALRepository extends AbstractDBALRepository implements TargetAudienceInterface
{
    public function getTargetAudience(
        string $externalId
    ): ?TargetAudience {
        // TODO: Implement getTargetAudience() method.
        return null;
    }

    public function saveTargetAudience(
        string $externalId,
        TargetAudience $targetAudience
    ): void {
        // TODO: Implement saveTargetAudience() method.
    }
}
