<?php

namespace CultuurNet\MovieApiFetcher\Date;

interface DateFactoryInterface
{
    /**
     * @param $dates
     * @return mixed
     */
    public function processDates($dates);

    /**
     * @param $day
     * @param $programmation
     * @return mixed
     */
    public function processDay($day, $programmation);
}
