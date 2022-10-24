<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Stores\NameInterface;

class StoreNameDBALRepository extends AbstractDBALRepository implements NameInterface
{
    public function getName(string $externalId): ?string
    {
        $whereId = SchemaNameConfigurator::EXTERNAL_ID_COLUMN . ' = :externalId';

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select(SchemaNameConfigurator::NAME_COLUMN)
            ->from($this->getTableName())
            ->where($whereId)
            ->setParameter('externalId', $externalId);

        $statement = $queryBuilder->execute();
        $resultSet = $statement->fetchAll();

        if (empty($resultSet)) {
            return null;
        }

        return $resultSet[0]['name_id'];
    }

    public function saveName(string $externalId, string $name): void
    {
        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->insert($this->getTableName())
            ->values([
                SchemaNameConfigurator::EXTERNAL_ID_COLUMN => '?',
                SchemaNameConfigurator::NAME_COLUMN => '?',
            ])
            ->setParameters([
                $externalId,
                $name,
            ]);

        $queryBuilder->execute();
    }

    public function updateName(string $externalId, string $name): void
    {
        $whereId = SchemaNameConfigurator::EXTERNAL_ID_COLUMN . ' = :external_id';

        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->update($this->getTableName())
            ->set(
                SchemaNameConfigurator::NAME_COLUMN,
                ':name_id'
            )
            ->where($whereId)
            ->setParameters([
                SchemaNameConfigurator::EXTERNAL_ID_COLUMN => $externalId,
                SchemaNameConfigurator::NAME_COLUMN => $name,
            ]);

        $queryBuilder->execute();
    }
}
