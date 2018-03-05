<?php

use CultuurNet\MovieApiFetcher\Authentication\Authentication;
use CultuurNet\MovieApiFetcher\DatabaseSchemaInstaller;
use CultuurNet\MovieApiFetcher\Date\DateFactory;
use CultuurNet\MovieApiFetcher\EntryPoster\EntryPoster;
use CultuurNet\MovieApiFetcher\Fetcher\Fetcher;
use CultuurNet\MovieApiFetcher\Formatter\Formatter;
use CultuurNet\MovieApiFetcher\Identification\IdentificationFactory;
use CultuurNet\MovieApiFetcher\Parser\Parser;
use CultuurNet\MovieApiFetcher\Price\PriceFactory;
use CultuurNet\MovieApiFetcher\ProductionPoster\ProductionPoster;
use CultuurNet\MovieApiFetcher\Term\TermFactory;
use CultuurNet\MovieApiFetcher\Theater\TheaterFactory;
use CultuurNet\MovieApiFetcher\Url\UrlFactory;
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
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreAgeRangeDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreBookingInfoDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreCalendarDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreContactPointDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreDescriptionDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreEventProductionDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreImageDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreLabelDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreLocationDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreNameDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreOrganizerDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StorePriceDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreRelationDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreTargetAudienceDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreThemeDBALRepository;
use CultuurNet\TransformEntryStore\Stores\Doctrine\StoreTypeDBALRepository;
use CultuurNet\TransformEntryStore\Stores\StoreRepository;
use DerAlex\Silex\YamlConfigServiceProvider;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Migrations\Configuration\YamlConfiguration;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Silex\Application;
use ValueObjects\StringLiteral\StringLiteral;
use ValueObjects\Web\Url;

$app = new Application();

if (!isset($appConfigLocation)) {
    $appConfigLocation =  __DIR__;
}
$app->register(new YamlConfigServiceProvider($appConfigLocation . '/config.yml'));
$app->register(new YamlConfigServiceProvider($appConfigLocation . '/kinepolis_terms.yml'));
$app->register(new YamlConfigServiceProvider($appConfigLocation . '/kinepolis_theaters.yml'));

/**
 * Turn debug on or off.
 */
$app['debug'] = $app['config']['debug'] === true;

$app['dbal_connection'] = $app->share(
    function (Application $app) {
        return DriverManager::getConnection(
            $app['config']['database'],
            null
        );
    }
);

$app['database.migrations.configuration'] = $app->share(
    function (Application $app) {
        /** @var \Doctrine\DBAL\Connection $connection */
        $connection = $app['dbal_connection'];

        $configuration = new YamlConfiguration($connection);
        $configuration->load(__DIR__ . '/migrations.yml');

        return $configuration;
    }
);

$app['database.installer'] = $app->share(
    function (Application $app) {
        $installer = new DatabaseSchemaInstaller(
            $app['dbal_connection'],
            $app['database.migrations.configuration']
        );

        $installer->addSchemaConfigurator(
            new SchemaAgeRangeConfigurator(new StringLiteral('age_range'))
        );

        $installer->addSchemaConfigurator(
            new SchemaBookingInfoConfigurator(new StringLiteral('booking_info'))
        );

        $installer->addSchemaConfigurator(
            new SchemaCalendarConfigurator(new StringLiteral('calendar'))
        );

        $installer->addSchemaConfigurator(
            new SchemaContactPointEmailConfigurator(new StringLiteral('email'))
        );

        $installer->addSchemaConfigurator(
            new SchemaContactPointPhoneConfigurator(new StringLiteral('phone'))
        );

        $installer->addSchemaConfigurator(
            new SchemaContactPointUrlConfigurator(new StringLiteral('url'))
        );

        $installer->addSchemaConfigurator(
            new SchemaDescriptionConfigurator(new StringLiteral('description'))
        );

        $installer->addSchemaConfigurator(
            new SchemaEventProductionConfigurator(new StringLiteral('event_production'))
        );

        $installer->addSchemaConfigurator(
            new SchemaImageConfigurator(new StringLiteral('image'))
        );

        $installer->addSchemaConfigurator(
            new SchemaLabelConfigurator(new StringLiteral('label'))
        );

        $installer->addSchemaConfigurator(
            new SchemaLocationConfigurator(new StringLiteral('location'))
        );

        $installer->addSchemaConfigurator(
            new SchemaNameConfigurator(new StringLiteral('name'))
        );

        $installer->addSchemaConfigurator(
            new SchemaOrganizerConfigurator(new StringLiteral('organizer'))
        );

        $installer->addSchemaConfigurator(
            new SchemaPriceInfoConfigurator(new StringLiteral('price_info'))
        );

        $installer->addSchemaConfigurator(
            new SchemaProductionConfigurator(new StringLiteral('production'))
        );

        $installer->addSchemaConfigurator(
            new SchemaRelationConfigurator(new StringLiteral('relation'))
        );

        $installer->addSchemaConfigurator(
            new SchemaTargetAudienceConfigurator(new StringLiteral('target_audience'))
        );

        $installer->addSchemaConfigurator(
            new SchemaThemeConfigurator(new StringLiteral('theme'))
        );

        $installer->addSchemaConfigurator(
            new SchemaTypeConfigurator(new StringLiteral('type'))
        );

        return $installer;
    }
);

$app['log_handler_entry'] = $app->share(
    function (Application $app) {
        return new RotatingFileHandler(
            $app['config']['logging_folder'] . '/entry.log',
            365,
            Logger::DEBUG
        );
    }
);

$app['logger_entry'] = $app->share(
    function (Application $app) {
        return new Logger('importer', array($app['log_handler_entry']));
    }
);

$app['log_handler_fetcher'] = $app->share(
    function (Application $app) {
        return new RotatingFileHandler(
            $app['config']['logging_folder'] . '/fetcher.log',
            365,
            Logger::DEBUG
        );
    }
);

$app['logger_fetcher'] = $app->share(
    function (Application $app) {
        return new Logger('importer', array($app['log_handler_fetcher']));
    }
);

$app['log_handler_parser'] = $app->share(
    function (Application $app) {
        return new RotatingFileHandler(
            $app['config']['logging_folder'] . '/parser.log',
            365,
            Logger::DEBUG
        );
    }
);

$app['logger_parser'] = $app->share(
    function (Application $app) {
        return new Logger('importer', array($app['log_handler_parser']));
    }
);

$app['log_handler_production'] = $app->share(
    function (Application $app) {
        return new RotatingFileHandler(
            $app['config']['logging_folder'] . '/production.log',
            365,
            Logger::DEBUG
        );
    }
);

$app['logger_production'] = $app->share(
    function (Application $app) {
        return new Logger('importer', array($app['log_handler_production']));
    }
);

$app['url_factory'] = $app->share(
    function (Application $app) {
        return new UrlFactory(
            new StringLiteral($app['config']['kinepolis']['url'])
        );
    }
);


$app['authentication'] = $app->share(
    function (Application $app) {
        return new Authentication($app['url_factory']);
    }
);

$app['terms'] = $app->share(
    function (Application $app) {
        return new TermFactory($app['config']['kinepolis_terms']);
    }
);

$app['theaters'] = $app->share(
    function (Application $app) {
        return new TheaterFactory($app['config']['kinepolis_theaters']);
    }
);

$app['date_factory'] = $app->share(
    function (Application $app) {
        return new DateFactory();
    }
);

$app['entry_poster'] = $app->share(
    function (Application $app) {
        return new EntryPoster(
            $app['config']['publiq']['authentication']['token'],
            $app['config']['publiq']['authentication']['api_key'],
            $app['config']['publiq']['url'],
            $app['config']['files_folder'],
            $app['logger_entry']
        );
    }
);

$app['formatter'] = $app->share(
    function (Application $app) {
        return new Formatter(
            $app['repository'],
            $app['config']['publiq']['url']
        );
    }
);

$app['identification_factory'] = $app->share(
    function () {
        return new IdentificationFactory();
    }
);

$app['production_poster'] = $app->share(
    function (Application $app) {
        return new ProductionPoster(
            $app['logger_production'],
            Url::fromNative($app['config']['sapi2']['url'])
        );
    }
);

$app['dbal_store.age_range'] = $app->share(
    function (Application $app) {
        return new StoreAgeRangeDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('age_range')
        );
    }
);

$app['dbal_store.booking_info'] = $app->share(
    function (Application $app) {
        return new StoreBookingInfoDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('booking_info')
        );
    }
);

$app['dbal_store.calendar'] = $app->share(
    function (Application $app) {
        return new StoreCalendarDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('calendar')
        );
    }
);

$app['dbal_store.contact_point'] = $app->share(
    function (Application $app) {
        return new StoreContactPointDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('contact_point')
        );
    }
);

$app['dbal_store.description'] = $app->share(
    function (Application $app) {
        return new StoreDescriptionDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('description')
        );
    }
);

$app['dbal_store.event_production'] = $app->share(
    function (Application $app) {
        return new StoreEventProductionDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('event_production')
        );
    }
);

$app['dbal_store.image'] = $app->share(
    function (Application $app) {
        return new StoreImageDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('image')
        );
    }
);

$app['dbal_store.label'] = $app->share(
    function (Application $app) {
        return new StoreLabelDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('label')
        );
    }
);

$app['dbal_store.location'] = $app->share(
    function (Application $app) {
        return new StoreLocationDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('location')
        );
    }
);

$app['dbal_store.name'] = $app->share(
    function (Application $app) {
        return new StoreNameDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('name')
        );
    }
);

$app['dbal_store.organizer'] = $app->share(
    function (Application $app) {
        return new StoreOrganizerDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('organizer')
        );
    }
);

$app['dbal_store.price_info'] = $app->share(
    function (Application $app) {
        return new StorePriceDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('price_info')
        );
    }
);

$app['dbal_store.relation'] = $app->share(
    function (Application $app) {
        return new StoreRelationDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('relation')
        );
    }
);

$app['dbal_store.target_audience'] = $app->share(
    function (Application $app) {
        return new StoreTargetAudienceDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('target_audience')
        );
    }
);

$app['dbal_store.theme'] = $app->share(
    function (Application $app) {
        return new StoreThemeDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('theme')
        );
    }
);

$app['dbal_store.type'] = $app->share(
    function (Application $app) {
        return new StoreTypeDBALRepository(
            $app['dbal_connection'],
            new StringLiteral('type')
        );
    }
);

$app['repository'] = $app->share(
    function (Application $app) {
        return new StoreRepository(
            $app['dbal_store.age_range'],
            $app['dbal_store.booking_info'],
            $app['dbal_store.calendar'],
            $app['dbal_store.contact_point'],
            $app['dbal_store.description'],
            $app['dbal_store.event_production'],
            $app['dbal_store.image'],
            $app['dbal_store.label'],
            $app['dbal_store.location'],
            $app['dbal_store.name'],
            $app['dbal_store.organizer'],
            $app['dbal_store.price_info'],
            $app['dbal_store.relation'],
            $app['dbal_store.target_audience'],
            $app['dbal_store.theme'],
            $app['dbal_store.type']
        );
    }
);

$app['parser'] = $app->share(
    function (Application $app) {
        return new Parser(
            $app['date_factory'],
            $app['entry_poster'],
            $app['formatter'],
            $app['identification_factory'],
            $app['production_poster'],
            $app['terms'],
            $app['theaters'],
            $app['url_factory'],
            $app['repository'],
            $app['logger_parser']
        );
    }
);

$app['price_factory'] = $app->share(
    function () {
        return new PriceFactory();

    }
);

$app['fetcher'] = $app->share(
    function (Application $app) {
        return new Fetcher(
            new StringLiteral($app['config']['kinepolis']['authentication']['key']),
            new StringLiteral($app['config']['kinepolis']['authentication']['secret']),
            $app['authentication'],
            $app['url_factory'],
            $app['parser'],
            $app['price_factory'],
            $app['logger_fetcher']
        );
    }
);

return $app;
