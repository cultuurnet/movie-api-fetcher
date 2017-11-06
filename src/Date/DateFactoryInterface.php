<?php

namespace CultuurNet\MovieApiFetcher\Date;

interface DateFactoryInterface
{
    /**
     * @param $dates
     * @param $length
     * @return mixed
     */
    public function processDates($dates, $length);

    /**
     * @param $day
     * @param $timeList
     * @return mixed
     */
    public function processDay($day, $timeList);
}
