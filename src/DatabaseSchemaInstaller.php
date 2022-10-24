<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher;

use CultuurNet\TransformEntryStore\Doctrine\DBAL\SchemaConfiguratorInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Migrations\Configuration\Configuration;

class DatabaseSchemaInstaller
{
    /**
     * @var SchemaConfiguratorInterface[]
     */
    protected $schemaConfigurators;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var Configuration
     */
    private $migrations;

    public function __construct(Connection $connection, Configuration $migrations)
    {
        $this->connection = $connection;
        $this->migrations = $migrations;

        $this->schemaConfigurators = [];
    }

    public function addSchemaConfigurator(
        SchemaConfiguratorInterface $schemaConfigurator
    ) {
        $this->schemaConfigurators[] = $schemaConfigurator;
    }

    public function installSchema()
    {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $this->connection;

        $schemaManager = $connection->getSchemaManager();

        foreach ($this->schemaConfigurators as $configurator) {
            $configurator->configure($schemaManager);
        }

        $this->markVersionsMigrated();
    }

    private function markVersionsMigrated()
    {
        foreach ($this->migrations->getAvailableVersions(
        ) as $versionIdentifier) {
            $version = $this->migrations->getVersion($versionIdentifier);

            $version->markMigrated();
        }
    }
}
