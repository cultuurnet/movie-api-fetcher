<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Stores\EventProductionInterface;
use Doctrine\DBAL\Exception;
use ValueObjects\Identity\UUID;

class StoreEventProductionDBALRepository extends AbstractDBALRepository implements EventProductionInterface
{
    /**
     * @throws Exception
     */
    public function getCdbids(string $externalId): ?array
    {
        $whereId = SchemaEventProductionConfigurator::EXTERNAL_ID_PRODUCTION_COLUMN . ' = :externalId';

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select(SchemaEventProductionConfigurator::CDBID_EVENT_COLUMN)
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

    public function saveEventProduction(
        string $externalIdEvent,
        string $externalIdProduction,
        UUID $cdbid
    ): void {
        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->insert($this->getTableName())
            ->values([

                SchemaEventProductionConfigurator::EXTERNAL_ID_EVENT_COLUMN => '?',
                SchemaEventProductionConfigurator::EXTERNAL_ID_PRODUCTION_COLUMN => '?',
                SchemaEventProductionConfigurator::CDBID_EVENT_COLUMN => '?',
            ])
            ->setParameters([
                $externalIdEvent,
                $externalIdProduction,
                $cdbid,
            ]);

        $queryBuilder->execute();
    }
}
