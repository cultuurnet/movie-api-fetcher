<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores\Doctrine;

use CultuurNet\TransformEntryStore\Doctrine\DBAL\SchemaConfiguratorInterface;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Doctrine\DBAL\Types\Type;

class SchemaBookingInfoConfigurator implements SchemaConfiguratorInterface
{
    public const EXTERNAL_ID_COLUMN = 'external_id';
    public const URL_COLUMN = 'url';
    public const EMAIL_COLUMN = 'email';
    public const PHONE_COLUMN = 'phone';
    public const AVAILABILITY_STARTS_COLUMN = 'availabilityStarts';
    public const AVAILABILITY_ENDS_COLUMN = 'availabilityEnds';

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
        $table->addColumn(self::URL_COLUMN, Type::STRING)
            ->setLength(128)
            ->setNotnull(true);
        $table->addColumn(self::EMAIL_COLUMN, TYPE::STRING)
            ->setLength(128)
            ->setNotnull(true);
        $table->addColumn(self::PHONE_COLUMN, TYPE::STRING)
            ->setLength(128)
            ->setNotnull(true);
        $table->addColumn(self::AVAILABILITY_STARTS_COLUMN, TYPE::DATETIMETZ)
            ->setNotnull(true);
        $table->addColumn(self::AVAILABILITY_ENDS_COLUMN, TYPE::DATETIMETZ)
            ->setNotnull(true);

        $table->addUniqueIndex([self::EXTERNAL_ID_COLUMN]);

        $schemaManager->createTable($table);
    }
}
