<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Doctrine\DBAL\SchemaConfiguratorInterface;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Types\Type;

class SchemaCalendarConfigurator implements SchemaConfiguratorInterface
{
    public const EXTERNAL_ID_COLUMN = 'external_id';
    public const DATE_COLUMN = 'date';
    public const TIME_START_COLUMN = 'time_start';
    public const TIME_END_COLUMN = 'time_end';

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
        $table->addColumn(self::DATE_COLUMN, Type::DATE)
            ->setNotnull(true);
        $table->addColumn(self::TIME_START_COLUMN, TYPE::TIME)
            ->setNotnull(true);
        $table->addColumn(self::TIME_END_COLUMN, TYPE::TIME);

        $schemaManager->createTable($table);
    }
}
