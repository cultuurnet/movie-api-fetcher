<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Stores\DescriptionRepositoryInterface;

class StoreDescriptionDBALRepository extends AbstractDBALRepository implements DescriptionRepositoryInterface
{
    public function getDescription(
        string $externalId
    ): ?string {
        $whereId = SchemaDescriptionConfigurator::EXTERNAL_ID_COLUMN . ' = :externalId';

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select(SchemaDescriptionConfigurator::DESCRIPTION_COLUMN)
            ->from($this->getTableName())
            ->where($whereId)
            ->setParameter('externalId', $externalId);

        $statement = $queryBuilder->execute();
        $resultSet = $statement->fetchAll();

        if (empty($resultSet)) {
            return null;
        }

        return $resultSet[0]['description_id'];
    }

    public function saveDescription(
        string $externalId,
        string $description
    ): void {
        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->insert($this->getTableName())
            ->values([
                SchemaDescriptionConfigurator::EXTERNAL_ID_COLUMN => '?',
                SchemaDescriptionConfigurator::DESCRIPTION_COLUMN => '?',
            ])
            ->setParameters([
                $externalId,
                $description,
            ]);

        $queryBuilder->execute();
    }

    public function updateDescription(
        string $externalId,
        string $description
    ): void {
        $whereId = SchemaDescriptionConfigurator::EXTERNAL_ID_COLUMN . ' = :external_id';

        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->update($this->getTableName())
            ->set(
                SchemaDescriptionConfigurator::DESCRIPTION_COLUMN,
                ':description_id'
            )
            ->where($whereId)
            ->setParameters([
                SchemaDescriptionConfigurator::EXTERNAL_ID_COLUMN => $externalId,
                SchemaDescriptionConfigurator::DESCRIPTION_COLUMN => $description,
            ]);

        $queryBuilder->execute();
    }
}
