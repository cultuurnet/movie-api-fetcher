<?php

namespace CultuurNet\MovieApiFetcher\Date;

class DateFactory implements DateFactoryInterface
{
    /**
     * @inheritdoc
     */
    public function processDates($dates)
    {
        foreach ($dates as $day => $programmation) {
            $this->processDay($day, $programmation);
        }
    }

    /**
     * @inheritdoc
     */
    public function processDay($day, $programmation)
    {
        var_dump($day);
        foreach ($programmation as $info) {
            var_dump($info);
        }
    }
}
