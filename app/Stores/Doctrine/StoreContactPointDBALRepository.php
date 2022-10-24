<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Stores\ContactPointInterface;
use CultuurNet\TransformEntryStore\ValueObjects\ContactPoint\ContactPoint;

class StoreContactPointDBALRepository extends AbstractDBALRepository implements ContactPointInterface
{
    public function getContactPoints(
        string $externalId
    ): ?array {
        // TODO: Implement getContactPoints() method.
        return null;
    }


    public function saveContactPoint(
        string $externalId,
        ContactPoint $contactPoint
    ): void {
        // TODO: Implement saveContactPoint() method.
    }


    public function updateContactPoint(
        string $externalId,
        ContactPoint $contactPoint
    ): void {
        // TODO: Implement updateContactPoint() method.
    }
}
