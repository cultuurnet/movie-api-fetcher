<?php

use CultuurNet\MovieApiFetcher\Authentication\Authentication;
use CultuurNet\MovieApiFetcher\Fetcher\Fetcher;
use CultuurNet\MovieApiFetcher\Parser\Parser;
use CultuurNet\MovieApiFetcher\Url\UrlFactory;
use DerAlex\Silex\YamlConfigServiceProvider;
use Silex\Application;
use ValueObjects\StringLiteral\StringLiteral;

$app = new Application();

if (!isset($appConfigLocation)) {
    $appConfigLocation =  __DIR__;
}
$app->register(new YamlConfigServiceProvider($appConfigLocation . '/config.yml'));

/**
 * Turn debug on or off.
 */
$app['debug'] = $app['config']['debug'] === true;

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

$app['parser'] = $app->share(
    function (Application $app) {
        return new Parser();
    }
);

$app['fetcher'] = $app->share(
    function (Application $app) {
        return new Fetcher(
            new StringLiteral($app['config']['kinepolis']['authentication']['key']),
            new StringLiteral($app['config']['kinepolis']['authentication']['secret']),
            $app['authentication'],
            $app['url_factory'],
            $app['parser']
        );
    }
);

return $app;
