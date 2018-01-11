<?php

namespace CultuurNet\MovieApiFetcher\Formatter;

use ValueObjects\StringLiteral\StringLiteral;

interface FormatterInterface
{
    /**
     * @param $name
     * @param $type
     * @param $theme
     * @param $location
     * @param $calendar
     * @return StringLiteral
     */
    public function format($name, $type, $theme, $location, $calendar);

    /**
     * @param $externalId
     * @return StringLiteral
     */
    public function formatEvent($externalId);
}
