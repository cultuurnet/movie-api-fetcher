<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Stores\TypeRepositoryInterface;

class StoreTypeDBALRepository extends AbstractDBALRepository implements TypeRepositoryInterface
{
    public function getTypeId(
        string $externalId
    ): ?string {
        $whereId = SchemaTypeConfigurator::EXTERNAL_ID_COLUMN . ' = :externalId';

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select(SchemaTypeConfigurator::TYPE_ID_COLUMN)
            ->from($this->getTableName())
            ->where($whereId)
            ->setParameter('externalId', $externalId);

        $statement = $queryBuilder->execute();
        $resultSet = $statement->fetchAll();

        if (empty($resultSet)) {
            return null;
        }

        return $resultSet[0]['type_id'];
    }

    public function saveTypeId(
        string $externalId,
        string $typeId
    ): void {
        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->insert($this->getTableName())
            ->values([

                SchemaTypeConfigurator::EXTERNAL_ID_COLUMN => '?',
                SchemaTypeConfigurator::TYPE_ID_COLUMN => '?',
            ])
            ->setParameters([
                $externalId,
                $typeId,
            ]);

        $queryBuilder->execute();
    }

    public function updateTypeId(
        string $externalId,
        string $typeId
    ): void {
        // TODO: Implement updateTypeId() method.
    }
}
