<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Stores\CalendarInterface;

class StoreCalendarDBALRepository extends AbstractDBALRepository implements CalendarInterface
{
    public function getCalendar(
        string $externalId
    ): ?array {
        $whereId = SchemaCalendarConfigurator::EXTERNAL_ID_COLUMN . ' = :externalId';

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select(
            SchemaCalendarConfigurator::DATE_COLUMN,
            SchemaCalendarConfigurator::TIME_START_COLUMN,
            SchemaCalendarConfigurator::TIME_END_COLUMN
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

    public function deleteCalendar(
        string $externalId
    ): void {
        // TODO: Implement deleteCalendar() method.
    }

    public function saveCalendar(
        string $externalId,
        $date,
        $timeStart,
        $timeEnd
    ): void {
        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->insert($this->getTableName())
            ->values([
                SchemaCalendarConfigurator::EXTERNAL_ID_COLUMN => '?',
                SchemaCalendarConfigurator::DATE_COLUMN => '?',
                SchemaCalendarConfigurator::TIME_START_COLUMN => '?',
                SchemaCalendarConfigurator::TIME_END_COLUMN => '?',
            ])
            ->setParameters([
                $externalId,
                $date,
                $timeStart,
                $timeEnd,
            ]);

        $queryBuilder->execute();
    }
}
