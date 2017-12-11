<?php

use CultuurNet\MovieApiFetcher\Authentication\Authentication;
use CultuurNet\MovieApiFetcher\DatabaseSchemaInstaller;
use CultuurNet\MovieApiFetcher\Date\DateFactory;
use CultuurNet\MovieApiFetcher\Fetcher\Fetcher;
use CultuurNet\MovieApiFetcher\Identification\IdentificationFactory;
use CultuurNet\MovieApiFetcher\Parser\Parser;
use CultuurNet\MovieApiFetcher\Term\TermFactory;
use CultuurNet\MovieApiFetcher\Theater\TheaterFactory;
use CultuurNet\MovieApiFetcher\Url\UrlFactory;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaAgeRangeConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaBookingInfoConfigurator;
use CultuurNet\TransformEntryStore\Stores\Doctrine\SchemaCalendarConfigurator;
use DerAlex\Silex\YamlConfigServiceProvider;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Migrations\Configuration\YamlConfiguration;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Silex\Application;
use ValueObjects\StringLiteral\StringLiteral;

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

        return $installer;
    }
);

$app['log_handler'] = $app->share(
    function (Application $app) {
        return new RotatingFileHandler(
            $app['config']['logging_folder'] . '/fetcher.log',
            180,
            Logger::DEBUG
        );
    }
);

$app['logger'] = $app->share(
    function (Application $app) {
        return new Logger('importer', array($app['log_handler']));
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

$app['identification_factory'] = $app->share(
    function () {
        return new IdentificationFactory();
    }
);

$app['parser'] = $app->share(
    function (Application $app) {
        return new Parser(
            $app['date_factory'],
            $app['identification_factory'],
            $app['terms'],
            $app['theaters'],
            $app['url_factory']
        );
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
            $app['logger']
        );
    }
);

return $app;
