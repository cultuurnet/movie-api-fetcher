<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores;

interface PriceInterface
{
    public function getPrice(
        string $externalId
    ): ?array;

    public function deletePrice(
        string $externalId
    );

    /**
     * @param $isBasePrice
     * @param $name
     * @param $price
     * @param $currency
     */
    public function savePrice(
        string $externalId,
        $isBasePrice,
        $name,
        $price,
        $currency
    ): void;

    /**
     * @param $isBasePrice
     * @param $name
     * @param $price
     * @param $currency
     */
    public function updatePrice(
        string $externalId,
        $isBasePrice,
        $name,
        $price,
        $currency
    ): void;
}
