<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Doctrine\DBAL\SchemaConfiguratorInterface;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Types\Type;

class SchemaPriceInfoConfigurator implements SchemaConfiguratorInterface
{
    public const EXTERNAL_ID_COLUMN = 'external_id';
    public const IS_BASE_PRICE_COLUMN = 'is_base_price';
    public const NAME_COLUMN = 'name';
    public const PRICE_COLUMN = 'price';
    public const CURRENCY_COLUMN = 'currency';

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
        $table->addColumn(self::IS_BASE_PRICE_COLUMN, Type::BOOLEAN)
            ->setNotnull(true);
        $table->addColumn(self::NAME_COLUMN, Type::STRING)
            ->setLength(128)
            ->setNotnull(true);
        $table->addColumn(self::PRICE_COLUMN, TYPE::DECIMAL)
            ->setScale(2)
            ->setNotnull(true);
        $table->addColumn(self::CURRENCY_COLUMN, TYPE::STRING)
            ->setLength(3)
            ->setNotnull(true);


        $schemaManager->createTable($table);
    }
}
