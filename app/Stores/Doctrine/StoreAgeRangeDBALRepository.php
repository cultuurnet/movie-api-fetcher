<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Stores\AgeRangeInterface;
use CultuurNet\TransformEntryStore\ValueObjects\AgeRange\AgeRange;
use ValueObjects\Identity\UUID;

class StoreAgeRangeDBALRepository extends AbstractDBALRepository implements AgeRangeInterface
{
    public function getAgeRange(
        string $externalId
    ): ?UUID {
        $whereId =  SchemaAgeRangeConfigurator::EXTERNAL_ID_COLUMN . ' = :externalId';

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select(SchemaAgeRangeConfigurator::AGE_FROM_COLUMN, SchemaAgeRangeConfigurator::AGE_TO_COLUMN)
            ->from($this->getTableName())
            ->where($whereId)
            ->setParameter('externalId', $externalId);

        $statement = $queryBuilder->execute();
        $resultSet = $statement->fetchAll();
        if (empty($resultSet)) {
            return null;
        }

        return UUID::fromNative($resultSet[0]);
    }

    public function saveAgeRange(
        string $externalId,
        AgeRange $ageRange
    ): void {
        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->insert($this->getTableName())
            ->values([
                SchemaAgeRangeConfigurator::EXTERNAL_ID_COLUMN => '?',
                SchemaAgeRangeConfigurator::AGE_FROM_COLUMN => '?',
                SchemaAgeRangeConfigurator::AGE_TO_COLUMN => '?',
            ])
            ->setParameters([
                $externalId,
                $ageRange->getAgeFrom(),
                $ageRange->getAgeTo(),
            ]);

        $queryBuilder->execute();
    }

    public function updateAgeRange(
        string $externalId,
        AgeRange $ageRange
    ): void {
        $whereId = SchemaAgeRangeConfigurator::EXTERNAL_ID_COLUMN . ' = :externalId';

        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->update($this->getTableName())
            ->set(
                SchemaAgeRangeConfigurator::AGE_FROM_COLUMN,
                ':ageFrom'
            )
            ->set(
                SchemaAgeRangeConfigurator::AGE_TO_COLUMN,
                ':ageTo'
            )
            ->where($whereId)
            ->setParameters([
                SchemaAgeRangeConfigurator::EXTERNAL_ID_COLUMN => $externalId,
                SchemaAgeRangeConfigurator::AGE_FROM_COLUMN => $ageRange->getAgeFrom(),
                SchemaAgeRangeConfigurator::AGE_TO_COLUMN => $ageRange->getAgeTo(),
            ]);

        $queryBuilder->execute();
    }
}
