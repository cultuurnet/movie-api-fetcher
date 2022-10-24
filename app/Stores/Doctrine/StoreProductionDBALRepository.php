<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Stores\ProductionInterface;
use ValueObjects\Identity\UUID;

class StoreProductionDBALRepository extends AbstractDBALRepository implements ProductionInterface
{
    public function getProductionCdbid(string $externalId): ?UUID
    {
        $whereId = SchemaRelationConfigurator::EXTERNAL_ID_COLUMN . ' = :externalId';

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select(SchemaRelationConfigurator::CDBID_COLUMN)
            ->from($this->getTableName())
            ->where($whereId)
            ->setParameter('externalId', $externalId);

        $statement = $queryBuilder->execute();
        $resultSet = $statement->fetchAll();

        if (empty($resultSet)) {
            return null;
        }

        $cdbid = $resultSet[0]['cdbid'];
        return UUID::fromNative($cdbid);
    }

    public function saveProductionCdbid(string $externalId, UUID $cdbid): void
    {
        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->insert($this->getTableName())
            ->values([
                SchemaRelationConfigurator::EXTERNAL_ID_COLUMN => '?',
                SchemaRelationConfigurator::CDBID_COLUMN => '?',
            ])
            ->setParameters([
                $externalId,
                $cdbid,
            ]);

        $queryBuilder->execute();
    }
}
