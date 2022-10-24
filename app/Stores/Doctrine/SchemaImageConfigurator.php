<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Doctrine\DBAL\SchemaConfiguratorInterface;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Types\Type;

class SchemaImageConfigurator implements SchemaConfiguratorInterface
{
    public const EXTERNAL_ID_COLUMN = 'external_id';
    public const IMAGE_ID_COLUMN = 'image_id';
    public const DESCRIPTION_COLUMN = 'description';
    public const COPYRIGHT_COLUMN = 'copyright';
    public const LANGUAGE_ID_COLUMN = 'language_id';

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
        $table->addColumn(self::IMAGE_ID_COLUMN, Type::GUID)
            ->setLength(36)
            ->setNotnull(true);
        $table->addColumn(self::DESCRIPTION_COLUMN, Type::STRING)
            ->setLength(256)
            ->setNotnull(true);
        $table->addColumn(self::COPYRIGHT_COLUMN, TYPE::STRING)
            ->setLength(256)
            ->setNotnull(true);
        $table->addColumn(self::LANGUAGE_ID_COLUMN, Type::STRING)
            ->setLength(2)
            ->setNotnull(true);

        $schemaManager->createTable($table);
    }
}
