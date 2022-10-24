<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Formatter;

interface FormatterInterface
{
    /**
     * @param $externalId
     */
    public function formatEvent($externalId): string;

    /**
     * @param $externalId
     */
    public function formatCalendar($externalId): string;

    /**
     * @param $externalId
     */
    public function formatPrice($externalId): string;

    public function formatProduction(string $externalIdProduction): string;

    public function formatProductionJson(string $externalIdProduction): string;
}
