<?php

use CultuurNet\MovieApiFetcher\Authentication\Authentication;
use CultuurNet\MovieApiFetcher\Date\DateFactory;
use CultuurNet\MovieApiFetcher\Fetcher\Fetcher;
use CultuurNet\MovieApiFetcher\Identification\IdentificationFactory;
use CultuurNet\MovieApiFetcher\Parser\Parser;
use CultuurNet\MovieApiFetcher\Term\TermFactory;
use CultuurNet\MovieApiFetcher\Theater\TheaterFactory;
use CultuurNet\MovieApiFetcher\Url\UrlFactory;
use DerAlex\Silex\YamlConfigServiceProvider;
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
