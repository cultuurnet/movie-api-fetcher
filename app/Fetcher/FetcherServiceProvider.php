<?php

namespace CultuurNet\TransformEntryStore\Fetcher;

use CultuurNet\MovieApiFetcher\Authentication\Authentication;
use CultuurNet\MovieApiFetcher\Fetcher\Fetcher;
use CultuurNet\MovieApiFetcher\Parser\Parser;
use CultuurNet\MovieApiFetcher\Price\PriceFactory;
use CultuurNet\MovieApiFetcher\Url\UrlFactory;
use CultuurNet\TransformEntryStore\Container\AbstractServiceProvider;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

final class FetcherServiceProvider extends AbstractServiceProvider
{
    protected function getProvidedServiceNames(): array
    {
        return [Fetcher::class];
    }

    public function register(): void
    {
        $container = $this->getContainer();

        $container->addShared(
            Fetcher::class,
            fn () => new Fetcher(
                $container->get('config')['kinepolis']['authentication']['key'],
                $container->get('config')['kinepolis']['authentication']['secret'],
                $container->get(Authentication::class),
                new UrlFactory($container->get('config')['kinepolis']['url']),
                $container->get(Parser::class),
                new PriceFactory(),
                new Logger(
                    'importer',
                    array(
                        new RotatingFileHandler(
                            $container->get('config')['logging_folder'] . '/fetcher.log',
                            365,
                            Logger::DEBUG
                        )
                    )
                ),
                $container->get('debug')
            )
        );
    }
}