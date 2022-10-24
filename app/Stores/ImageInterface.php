<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores;

use CultuurNet\TransformEntryStore\ValueObjects\Language\LanguageCode;
use ValueObjects\Identity\UUID;

interface ImageInterface
{
    public function getImageId(string $externalId): ?string;

    public function saveImage(
        string $externalId,
        UUID $imageId,
        string $description,
        string $copyright,
        LanguageCode $languageCode
    ): void;

    public function updateImage(
        string $externalId,
        UUID $imageId,
        string $description,
        string $copyright,
        LanguageCode $languageCode
    ): void;
}
