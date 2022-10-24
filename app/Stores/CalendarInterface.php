<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores;

interface CalendarInterface
{
    public function getCalendar(
        string $externalId
    ): ?array;

    public function deleteCalendar(
        string $externalId
    ): void;

    public function saveCalendar(
        string $externalId,
        $date,
        $timeStart,
        $timeEnd
    );
}
