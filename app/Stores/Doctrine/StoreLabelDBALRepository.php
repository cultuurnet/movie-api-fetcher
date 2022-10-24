<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Stores\LabelInterface;

class StoreLabelDBALRepository extends AbstractDBALRepository implements LabelInterface
{
    public function addLabel(string $externalId, string $label): void
    {
        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->insert($this->getTableName())
            ->values([
                SchemaLabelConfigurator::EXTERNAL_ID_COLUMN => '?',
                SchemaLabelConfigurator::LABEL_COLUMN => '?',
            ])
            ->setParameters([
                $externalId,
                $label,
            ]);

        $queryBuilder->execute();
    }

    public function deleteLabel(string $externalId, string $label): void
    {
        $queryBuilder = $this->createQueryBuilder();

        $queryBuilder->delete($this->getTableName())
            ->values([
                SchemaLabelConfigurator::EXTERNAL_ID_COLUMN => '?',
                SchemaLabelConfigurator::LABEL_COLUMN => '?',
            ])
            ->setParameters([
                $externalId,
                $label,
            ]);

        $queryBuilder->execute();
    }
}
