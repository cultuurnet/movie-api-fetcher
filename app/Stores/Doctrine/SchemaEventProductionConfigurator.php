<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Doctrine\DBAL\SchemaConfiguratorInterface;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Types\Type;

class SchemaEventProductionConfigurator implements SchemaConfiguratorInterface
{
    public const EXTERNAL_ID_EVENT_COLUMN = 'external_id_event';
    public const EXTERNAL_ID_PRODUCTION_COLUMN = 'external_id_production';
    public const CDBID_EVENT_COLUMN = 'cdbid_event';

    protected string $tableName;

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

    public function configure(AbstractSchemaManager $schemaManager): void
    {
        $schema = $schemaManager->createSchema();
        $table = $schema->createTable($this->tableName);

        $table->addColumn(self::EXTERNAL_ID_EVENT_COLUMN, Type::STRING)
            ->setLength(128)
            ->setNotnull(true);
        $table->addColumn(self::EXTERNAL_ID_PRODUCTION_COLUMN, Type::STRING)
            ->setLength(128)
            ->setNotnull(true);
        $table->addColumn(self::CDBID_EVENT_COLUMN, Type::GUID)
            ->setNotnull(true);


        $table->addUniqueIndex([self::EXTERNAL_ID_EVENT_COLUMN, self::CDBID_EVENT_COLUMN]);

        $schemaManager->createTable($table);
    }
}
