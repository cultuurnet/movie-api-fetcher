<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Stores\LocationInterface;
use ValueObjects\Identity\UUID;

class StoreLocationDBALRepository extends AbstractDBALRepository implements LocationInterface
{
    public function getLocationCdbid(string $externalId): ?string
    {
        $whereId = SchemaLocationConfigurator::EXTERNAL_ID_COLUMN . ' = :externalId';

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select(SchemaLocationConfigurator::LOCATION_CDBID_COLUMN)
            ->from($this->getTableName())
            ->where($whereId)
            ->setParameter('externalId', $externalId);

        $statement = $queryBuilder->execute();
        $resultSet = $statement->fetchAll();

        if (empty($resultSet)) {
            return null;
        }

        return $resultSet[0]['location_id'];
    }

    public function saveLocationCdbid(string $externalId, UUID $locationCdbid): void
    {
        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->insert($this->getTableName())
            ->values([

                SchemaLocationConfigurator::EXTERNAL_ID_COLUMN => '?',
                SchemaLocationConfigurator::LOCATION_CDBID_COLUMN => '?',
            ])
            ->setParameters([
                $externalId,
                $locationCdbid,
            ]);

        $queryBuilder->execute();
    }

    public function updateLocationCdbid(string $externalId, UUID $locationCdbid): void
    {
        $whereId = SchemaLocationConfigurator::EXTERNAL_ID_COLUMN . ' = :external_id';

        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->update($this->getTableName())
            ->set(
                SchemaLocationConfigurator::LOCATION_CDBID_COLUMN,
                ':location_id'
            )
            ->where($whereId)
            ->setParameters([
                SchemaLocationConfigurator::EXTERNAL_ID_COLUMN => $externalId,
                SchemaLocationConfigurator::LOCATION_CDBID_COLUMN => $locationCdbid,
            ]);

        $queryBuilder->execute();
    }
}
