<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Doctrine\DBAL\SchemaConfiguratorInterface;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Types\Type;

class SchemaProductionConfigurator implements SchemaConfiguratorInterface
{
    public const EXTERNAL_ID_COLUMN = 'external_id';
    public const PRODUCTION_COLUMN = 'cdbid';

    protected string $tableName;

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

    public function configure(AbstractSchemaManager $schemaManager): void
    {
        $schema = $schemaManager->createSchema();
        $table = $schema->createTable($this->tableName);

        $table->addColumn(self::PRODUCTION_COLUMN, Type::GUID)
            ->setLength(36);
        $table->addColumn(self::EXTERNAL_ID_COLUMN, Type::STRING)
            ->setLength(128)
            ->setNotnull(true);

        $table->addUniqueIndex([self::PRODUCTION_COLUMN]);
        $table->addUniqueIndex([self::EXTERNAL_ID_COLUMN]);

        $schemaManager->createTable($table);
    }
}
