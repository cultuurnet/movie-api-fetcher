<?php

namespace CultuurNet\TransformEntryStore\Database;

use CultuurNet\MovieApiFetcher\DatabaseSchemaInstaller;
use CultuurNet\TransformEntryStore\Container\AbstractServiceProvider;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaAgeRangeConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaBookingInfoConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaCalendarConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaContactPointEmailConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaContactPointPhoneConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaContactPointUrlConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaDescriptionConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaEventProductionConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaImageConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaLabelConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaLocationConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaNameConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaOrganizerConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaPriceInfoConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaProductionConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaRelationConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaTargetAudienceConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaThemeConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaTypeConfigurator;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Migrations\Configuration\YamlConfiguration;

final class DbServiceProvider extends AbstractServiceProvider
{
    protected function getProvidedServiceNames(): array
    {
        return [
            'dbal_connection',
            'db_migrations',
            DatabaseSchemaInstaller::class
        ];
    }

    public function register(): void
    {
        $container = $this->getContainer();

        $container->addShared(
            'dbal_connection',
            fn () => DriverManager::getConnection(
                $container->get('config')['database'],
                null
            )
        );

        $container->addShared(
            'db_migrations',
            function() use ($container) {
                /** @var Connection $connection */
                $connection = $container->get('dbal_connection');

                $configuration = new YamlConfiguration($connection);
                $configuration->load(__DIR__ . '/../../migrations.yml');

                return $configuration;
            }
        );

        $container->addShared(
            DatabaseSchemaInstaller::class,
            function() use ($container): DatabaseSchemaInstaller {
                $installer = new DatabaseSchemaInstaller(
                    $container->get('dbal_connection'),
                    $container->get('db_migrations')
                );

                $installer->addSchemaConfigurator(
                    new SchemaAgeRangeConfigurator('age_range')
                );

                $installer->addSchemaConfigurator(
                    new SchemaBookingInfoConfigurator('booking_info')
                );

                $installer->addSchemaConfigurator(
                    new SchemaCalendarConfigurator('calendar')
                );

                $installer->addSchemaConfigurator(
                    new SchemaContactPointEmailConfigurator('email')
                );

                $installer->addSchemaConfigurator(
                    new SchemaContactPointPhoneConfigurator('phone')
                );

                $installer->addSchemaConfigurator(
                    new SchemaContactPointUrlConfigurator('url')
                );

                $installer->addSchemaConfigurator(
                    new SchemaDescriptionConfigurator('description')
                );

                $installer->addSchemaConfigurator(
                    new SchemaEventProductionConfigurator('event_production')
                );

                $installer->addSchemaConfigurator(
                    new SchemaImageConfigurator('image')
                );

                $installer->addSchemaConfigurator(
                    new SchemaLabelConfigurator('label')
                );

                $installer->addSchemaConfigurator(
                    new SchemaLocationConfigurator('location')
                );

                $installer->addSchemaConfigurator(
                    new SchemaNameConfigurator('name')
                );

                $installer->addSchemaConfigurator(
                    new SchemaOrganizerConfigurator('organizer')
                );

                $installer->addSchemaConfigurator(
                    new SchemaPriceInfoConfigurator('price_info')
                );

                $installer->addSchemaConfigurator(
                    new SchemaProductionConfigurator('production')
                );

                $installer->addSchemaConfigurator(
                    new SchemaRelationConfigurator('relation')
                );

                $installer->addSchemaConfigurator(
                    new SchemaTargetAudienceConfigurator('target_audience')
                );

                $installer->addSchemaConfigurator(
                    new SchemaThemeConfigurator('theme')
                );

                $installer->addSchemaConfigurator(
                    new SchemaTypeConfigurator('type')
                );

                return $installer;
            }
        );
    }
}