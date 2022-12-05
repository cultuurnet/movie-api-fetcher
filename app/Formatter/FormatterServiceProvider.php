<?php

namespace CultuurNet\TransformEntryStore\Formatter;

use CultuurNet\MovieApiFetcher\Formatter\Formatter;
use CultuurNet\TransformEntryStore\Container\AbstractServiceProvider;
use CultuurNet\TransformEntryStore\Stores\StoreRepository;

final class FormatterServiceProvider extends AbstractServiceProvider
{
    protected function getProvidedServiceNames(): array
    {
        return [Formatter::class];
    }

    public function register(): void
    {
        $container = $this->getContainer();

        $container->addShared(
            Formatter::class,
            fn () => new Formatter(
                $container->get(StoreRepository::class),
                $container->get('config')['publiq']['url']
            )
        );
    }
}