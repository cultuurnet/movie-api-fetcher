<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Config;

use CultuurNet\MovieApiFetcher\Term\TermFactory;
use CultuurNet\MovieApiFetcher\Theater\TheaterFactory;
use CultuurNet\MovieApiFetcher\Url\UrlFactory;
use CultuurNet\TransformEntryStore\Container\AbstractServiceProvider;
use Symfony\Component\Yaml\Yaml;

final class ConfigServiceProvider extends AbstractServiceProvider
{
    protected function getProvidedServiceNames(): array
    {
        return [
            'config',
            TermFactory::class,
            TheaterFactory::class,
            UrlFactory::class,
            'debug',
        ];
    }

    public function register(): void
    {
        $container = $this->getContainer();

        $container->addShared(
            'config',
            fn () => Yaml::parse(file_get_contents(__DIR__ . '/../../config.yml'))
        );
        $container->addShared(
            TermFactory::class,
            fn () => new TermFactory(Yaml::parse(file_get_contents(__DIR__ . '/../../kinepolis_terms.yml')))
        );
        $container->addShared(
            TheaterFactory::class,
            fn () => new TheaterFactory(Yaml::parse(file_get_contents(__DIR__ . '/../../kinepolis_theaters.yml')))
        );
        $container->addShared(
            UrlFactory::class,
            fn () => new UrlFactory($container->get('config')['kinepolis']['url'])
        );
        $container->addShared(
            'debug',
            fn () => false
        );
    }
}
