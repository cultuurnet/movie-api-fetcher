<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores;

interface ThemeRepositoryInterface
{
    public function getThemeId(
        string $externalId
    ): ?string;


    public function saveThemeId(
        string $externalId,
        string $themeId
    ): void;

    public function updateThemeId(string $externalId, string $themeId): void;
}
