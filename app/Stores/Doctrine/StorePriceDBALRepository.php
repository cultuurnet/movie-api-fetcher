<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Stores\PriceInterface;

class StorePriceDBALRepository extends AbstractDBALRepository implements PriceInterface
{
    public function getPrice(string $externalId): ?array
    {
        $whereId = SchemaPriceInfoConfigurator::EXTERNAL_ID_COLUMN . ' = :externalId';

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select(
            SchemaPriceInfoConfigurator::IS_BASE_PRICE_COLUMN,
            SchemaPriceInfoConfigurator::NAME_COLUMN,
            SchemaPriceInfoConfigurator::PRICE_COLUMN,
            SchemaPriceInfoConfigurator::CURRENCY_COLUMN
        )
            ->from($this->getTableName())
            ->where($whereId)
            ->setParameter('externalId', $externalId);

        $statement = $queryBuilder->execute();
        $resultSet = $statement->fetchAll();

        if (empty($resultSet)) {
            return null;
        }

        return $resultSet;
    }

    public function deletePrice(
        string $externalId
    ): void {
        // TODO: Implement deletePrice() method.
    }

    public function savePrice(
        string $externalId,
        $isBasePrice,
        $name,
        $price,
        $currency
    ): void {
        $isBasePriceInt = $isBasePrice ? 1 : 0;
        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->insert($this->getTableName())
            ->values([
                SchemaPriceInfoConfigurator::EXTERNAL_ID_COLUMN => '?',
                SchemaPriceInfoConfigurator::IS_BASE_PRICE_COLUMN => '?',
                SchemaPriceInfoConfigurator::NAME_COLUMN => '?',
                SchemaPriceInfoConfigurator::PRICE_COLUMN => '?',
                SchemaPriceInfoConfigurator::CURRENCY_COLUMN => '?',
            ])
            ->setParameters([
                $externalId,
                $isBasePriceInt,
                $name,
                $price,
                $currency,
            ]);

        $queryBuilder->execute();
    }

    public function updatePrice(
        string $externalId,
        $isBasePrice,
        $name,
        $price,
        $currency
    ): void {
        $whereId = SchemaPriceInfoConfigurator::EXTERNAL_ID_COLUMN . ' = :external_id';
        $nameId = SchemaPriceInfoConfigurator::NAME_COLUMN . ' = :name';

        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->update($this->getTableName())
            ->set(
                SchemaPriceInfoConfigurator::PRICE_COLUMN,
                ':price'
            )
            ->where($whereId)
            ->andWhere($nameId)
            ->setParameters([
                SchemaPriceInfoConfigurator::NAME_COLUMN =>$name,
                SchemaPriceInfoConfigurator::EXTERNAL_ID_COLUMN => $externalId,
                SchemaPriceInfoConfigurator::PRICE_COLUMN => $price,
            ]);

        $queryBuilder->execute();
    }
}
