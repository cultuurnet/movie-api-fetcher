<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Doctrine\DBAL\SchemaConfiguratorInterface;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Types\Type;

class SchemaDescriptionConfigurator implements SchemaConfiguratorInterface
{
    public const EXTERNAL_ID_COLUMN = 'external_id';
    public const DESCRIPTION_COLUMN = 'description_id';

    protected string $tableName;

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

    public function configure(AbstractSchemaManager $schemaManager): void
    {
        $schema = $schemaManager->createSchema();
        $table = $schema->createTable($this->tableName);

        $table->addColumn(self::EXTERNAL_ID_COLUMN, Type::STRING)
            ->setLength(128)
            ->setNotnull(true);
        $table->addColumn(self::DESCRIPTION_COLUMN, Type::TEXT)
            ->setNotnull(true);

        $table->addUniqueIndex([self::EXTERNAL_ID_COLUMN]);

        $schemaManager->createTable($table);
    }
}
