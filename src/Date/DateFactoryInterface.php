<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Date;

interface DateFactoryInterface
{
    public function processDates($dates, $length);

    public function processDay($day, $timeList);
}
