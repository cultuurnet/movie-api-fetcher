<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Stores\ImageInterface;
use CultuurNet\TransformEntryStore\ValueObjects\Language\LanguageCode;
use ValueObjects\Identity\UUID;

class StoreImageDBALRepository extends AbstractDBALRepository implements ImageInterface
{
    public function getImageId($externalId): ?string
    {
        $whereId = SchemaImageConfigurator::EXTERNAL_ID_COLUMN . ' = :externalId';

        $queryBuilder = $this->createQueryBuilder();
        $queryBuilder->select(SchemaImageConfigurator::IMAGE_ID_COLUMN)
            ->from($this->getTableName())
            ->where($whereId)
            ->setParameter('externalId', $externalId);

        $statement = $queryBuilder->execute();
        $resultSet = $statement->fetchAll();

        if (empty($resultSet)) {
            return null;
        }

        return $resultSet[0]['image_id'];
    }

    public function saveImage(
        string $externalId,
        UUID $imageId,
        string $description,
        string $copyright,
        LanguageCode $languageCode
    ): void {
        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->insert($this->getTableName())
            ->values([
                SchemaImageConfigurator::EXTERNAL_ID_COLUMN => '?',
                SchemaImageConfigurator::IMAGE_ID_COLUMN => '?',
                SchemaImageConfigurator::DESCRIPTION_COLUMN => '?',
                SchemaImageConfigurator::COPYRIGHT_COLUMN => '?',
                SchemaImageConfigurator::LANGUAGE_ID_COLUMN => '?',
            ])
            ->setParameters([
                $externalId,
                $imageId,
                $description,
                $copyright,
                $languageCode,
            ]);

        $queryBuilder->execute();
    }

    public function updateImage(
        string $externalId,
        UUID $imageId,
        string $description,
        string $copyright,
        LanguageCode $languageCode
    ): void {
        $whereId = SchemaImageConfigurator::EXTERNAL_ID_COLUMN . ' = :external_id';

        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->update($this->getTableName())
            ->set(
                SchemaImageConfigurator::IMAGE_ID_COLUMN,
                ':image_id'
            )
            ->set(
                SchemaImageConfigurator::DESCRIPTION_COLUMN,
                ':description'
            )
            ->set(
                SchemaImageConfigurator::COPYRIGHT_COLUMN,
                ':copyright'
            )
            ->set(
                SchemaImageConfigurator::LANGUAGE_ID_COLUMN,
                ':language_id'
            )
            ->where($whereId)
            ->setParameters([
                SchemaImageConfigurator::EXTERNAL_ID_COLUMN => $externalId,
                SchemaImageConfigurator::IMAGE_ID_COLUMN =>$imageId,
                SchemaImageConfigurator::DESCRIPTION_COLUMN => $description,
                SchemaImageConfigurator::COPYRIGHT_COLUMN => $copyright,
                SchemaImageConfigurator::LANGUAGE_ID_COLUMN => $languageCode,
            ]);

        $queryBuilder->execute();
    }
}
