<?php

namespace CultuurNet\TransformEntryStore\EntryPoster;

use CultuurNet\MovieApiFetcher\EntryPoster\EntryPoster;
use CultuurNet\TransformEntryStore\Container\AbstractServiceProvider;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

final class EntryPosterServiceProvider extends AbstractServiceProvider
{
    protected function getProvidedServiceNames(): array
    {
        return [EntryPoster::class];
    }

    public function register(): void
    {
        $container = $this->getContainer();

        $container->addShared(
            EntryPoster::class,
            fn() => new EntryPoster(
                $container->get('config')['publiq']['authentication']['token_provider'],
                $container->get('config')['publiq']['authentication']['refresh'],
                $container->get('config')['publiq']['authentication']['api_key'],
                $container->get('config')['publiq']['url'],
                $container->get('config')['files_folder'],
                new Logger(
                    'importer',
                    array(
                        new RotatingFileHandler(
                            $container->get('config')['logging_folder'] . '/entry.log',
                            365,
                        Logger::DEBUG
                        )
                    )
                )
            )
        );
    }
}