<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores;

use CultuurNet\TransformEntryStore\ValueObjects\ContactPoint\ContactPoint;

interface ContactPointInterface
{
    public function getContactPoints(
        string $externalId
    ): ?array;

    public function saveContactPoint(
        string $externalId,
        ContactPoint $contactPoint
    ): void;

    public function updateContactPoint(
        string $externalId,
        ContactPoint $contactPoint
    ): void;
}
