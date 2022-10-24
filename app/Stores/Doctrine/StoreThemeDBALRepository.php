<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Stores\ThemeRepositoryInterface;

class StoreThemeDBALRepository extends AbstractDBALRepository implements ThemeRepositoryInterface
{
    public function getThemeId(
        string $externalId
    ): ?string {
        $whereId = SchemaThemeConfigurator::EXTERNAL_ID_COLUMN . ' = :externalId';

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select(SchemaThemeConfigurator::THEME_ID_COLUMN)
            ->from($this->getTableName())
            ->where($whereId)
            ->setParameter('externalId', $externalId);

        $statement = $queryBuilder->execute();
        $resultSet = $statement->fetchAll();

        if (empty($resultSet)) {
            return null;
        }

        return $resultSet[0]['theme_id'];
    }

    public function saveThemeId(
        string $externalId,
        string $themeId
    ): void {
        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->insert($this->getTableName())
            ->values([

                SchemaThemeConfigurator::EXTERNAL_ID_COLUMN => '?',
                SchemaThemeConfigurator::THEME_ID_COLUMN => '?',
            ])
            ->setParameters([
                $externalId,
                $themeId,
            ]);

        $queryBuilder->execute();
    }

    public function updateThemeId(string $externalId, string $themeId): void
    {
        // TODO: Implement updateThemeId() method.
    }
}
