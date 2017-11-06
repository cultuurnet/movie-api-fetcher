<?php

namespace CultuurNet\MovieApiFetcher\Date;

class DateFactory implements DateFactoryInterface
{
    /**
     * @var array
     */
    private $timeTableList;

    /**
     * @var
     */
    private $length;

    /**
     * @inheritdoc
     */
    public function processDates($dates, $length)
    {
        $this->timeTableList = array();
        $this->length = $length;

        foreach ($dates as $day => $timeList) {
            $this->processDay($day, $timeList);
        }
        return $this->timeTableList;
    }

    /**
     * @inheritdoc
     */
    public function processDay($day, $timeList)
    {
        foreach ($timeList as $info) {
            $this->timeTableList[$info['tid']][$day][] = array($info['time'], $this->getEndDate($info['time']));
        }
    }

    private function getEndDate($time)
    {
        $dt = \DateTime::createFromFormat('H:i:s', $time);
        $dt->add(new \DateInterval('PT'. $this->length . 'M'));
        return $dt->format('H:i:s');
    }
}
