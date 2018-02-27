<?php

namespace CultuurNet\MovieApiFetcher\Formatter;

use ValueObjects\StringLiteral\StringLiteral;

interface FormatterInterface
{
    /**
     * @param $externalId
     * @return StringLiteral
     */
    public function formatEvent($externalId);

    /**
     * @param $externalId
     * @return StringLiteral
     */
    public function formatCalendar($externalId);

    /**
     * @param $externalId
     * @return StringLiteral
     */
    public function formatPrice($externalId);

    /**
     * @param $externalId
     * @return StringLiteral
     */
    public function formatProduction($externalId);
}
