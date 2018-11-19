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

        if (isset($dates)) {
            foreach ($dates as $day => $timeList) {
                $this->processDay($day, $timeList);
            }
        }
        return $this->timeTableList;
    }

    /**
     * @inheritdoc
     */
    public function processDay($day, $timeList)
    {
        foreach ($timeList as $info) {
            $format = $this->getFormat($info['format']);
            $utcTime = $this->getUtcTime($day, $info['time']);
            $this->timeTableList[$info['tid']][$format][$day][] = array($utcTime, $this->getEndDate($utcTime));
        }
    }

    private function getEndDate($time)
    {
        $dt = \DateTime::createFromFormat('H:i:s', $time);
        try {
            $dt->add(new \DateInterval('PT' . $this->length . 'M'));
        } catch (\Exception $ex) {

        }
        return $dt->format('H:i:s');
    }

    private function getUtcTime($day, $time)
    {
        $timeZoneBrussels = new \DateTimeZone('Europe/Brussels');
        $timeZoneUtc = new \DateTimeZone('UTC');
        $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $day . ' ' . $time, $timeZoneBrussels);
        $dt->setTimezone($timeZoneUtc);
        return $dt->format('H:i:s');
    }

    private function getFormat($formatArray)
    {
        $formats3D = array(
            52,
            53,
            54,
            740,
            1033,
            1035,
            1036,
            1037,
            1045,
            1070,
            1093,
            1145,
            1147,
        );
        $is3D = array_intersect($formatArray, $formats3D);
        if (!isset($formatArray) || empty($formatArray) || empty($is3D)) {
            return '2D';
        } else {
            return '3D';
        }
    }
}
