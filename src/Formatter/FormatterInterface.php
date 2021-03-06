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
     * @param StringLiteral $externalIdProduction
     * @return StringLiteral
     */
    public function formatProduction(StringLiteral $externalIdProduction);

    /**
     * @param StringLiteral $externalIdProduction
     * @return StringLiteral
     */
    public function formatProductionJson(StringLiteral $externalIdProduction);
}
