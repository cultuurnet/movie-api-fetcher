<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores;

use ValueObjects\Identity\UUID;

interface OrganizerInterface
{
    public function getOrganizerCdbid(
        string $externalId
    ): ?UUID;

    public function saveOrganizerCdbid(
        string $externalId,
        UUID $organizerCdbid
    );

    public function updateOrganizerCdbid(
        string $externalId,
        UUID $organizerCdbid
    );
}
