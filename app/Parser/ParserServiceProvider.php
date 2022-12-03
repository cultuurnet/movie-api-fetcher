<?php

namespace CultuurNet\TransformEntryStore\Parser;

use CultuurNet\MovieApiFetcher\Date\DateFactory;
use CultuurNet\MovieApiFetcher\EntryPoster\EntryPoster;
use CultuurNet\MovieApiFetcher\Formatter\Formatter;
use CultuurNet\MovieApiFetcher\Identification\IdentificationFactory;
use CultuurNet\MovieApiFetcher\Parser\Parser;
use CultuurNet\MovieApiFetcher\Term\TermFactory;
use CultuurNet\MovieApiFetcher\Theater\TheaterFactory;
use CultuurNet\MovieApiFetcher\Url\UrlFactory;
use CultuurNet\TransformEntryStore\Container\AbstractServiceProvider;
use CultuurNet\TransformEntryStore\Stores\StoreRepository;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

final class ParserServiceProvider extends AbstractServiceProvider
{
    protected function getProvidedServiceNames(): array
    {
        return [Parser::class];
    }

    public function register(): void
    {
        $container = $this->getContainer();

        $container->addShared(
            Parser::class,
            fn() => new Parser(
                new DateFactory(),
                $container->get(EntryPoster::class),
                $container->get(Formatter::class),
                new IdentificationFactory(),
                $container->get(TermFactory::class),
                $container->get(TheaterFactory::class),
                new UrlFactory($container->get('config')['kinepolis']['url']),
                $container->get(StoreRepository::class),
                new Logger(
                    'importer',
                    array(
                        new RotatingFileHandler(
                        $container->get('config')['logging_folder'] . '/parser.log',
                        365,
                        Logger::DEBUG
                        )
                    )
                )
            )
        );
    }
}
