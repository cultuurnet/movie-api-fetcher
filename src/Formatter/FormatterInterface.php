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
     * @return StringLiteral
     */
    public function format($name, $type, $theme, $location);
}
