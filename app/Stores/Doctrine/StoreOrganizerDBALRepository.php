<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Stores\OrganizerInterface;
use ValueObjects\Identity\UUID;

class StoreOrganizerDBALRepository extends AbstractDBALRepository implements OrganizerInterface
{
    public function getOrganizerCdbid(string $externalId): ?UUID
    {
        $whereId = SchemaOrganizerConfigurator::EXTERNAL_ID_COLUMN . ' = :externalId';

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select(SchemaOrganizerConfigurator::ORGANIZER_CDBID_COLUMN)
            ->from($this->getTableName())
            ->where($whereId)
            ->setParameter('externalId', $externalId);

        $statement = $queryBuilder->execute();
        $resultSet = $statement->fetchAll();

        if (empty($resultSet)) {
            return null;
        }

        return $resultSet[0]['name'];
    }

    public function saveOrganizerCdbid(string $externalId, UUID $organizerCdbid): void
    {
        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->insert($this->getTableName())
            ->values([


                SchemaOrganizerConfigurator::EXTERNAL_ID_COLUMN => '?',
                SchemaOrganizerConfigurator::ORGANIZER_CDBID_COLUMN => '?',
            ])
            ->setParameters([
                $externalId,
                $organizerCdbid,
            ]);

        $queryBuilder->execute();
    }

    public function updateOrganizerCdbid(string $externalId, UUID $organizerCdbid): void
    {
        $whereId = SchemaLocationConfigurator::EXTERNAL_ID_COLUMN . ' = :external_id';

        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->update($this->getTableName())
            ->set(
                SchemaOrganizerConfigurator::ORGANIZER_CDBID_COLUMN,
                ':organizer_id'
            )
            ->where($whereId)
            ->setParameters([
                SchemaOrganizerConfigurator::EXTERNAL_ID_COLUMN => $externalId,
                SchemaOrganizerConfigurator::ORGANIZER_CDBID_COLUMN => $organizerCdbid,
            ]);

        $queryBuilder->execute();
    }
}
