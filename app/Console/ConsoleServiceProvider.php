<?php

namespace CultuurNet\TransformEntryStore\Console;

use CultuurNet\MovieApiFetcher\DatabaseSchemaInstaller;
use CultuurNet\MovieApiFetcher\Fetcher\Fetcher;
use CultuurNet\MovieApiFetcher\Formatter\Formatter;
use CultuurNet\TransformEntryStore\Console\Commands\FetchCommand;
use CultuurNet\TransformEntryStore\Console\Commands\InstallCommand;
use CultuurNet\TransformEntryStore\Console\Commands\TestCommand;
use CultuurNet\TransformEntryStore\Container\AbstractServiceProvider;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;
use Symfony\Component\Console\CommandLoader\ContainerCommandLoader;

final class ConsoleServiceProvider extends AbstractServiceProvider
{
    private const COMMAND_SERVICES = [
        'console.apifetcher',
        'console.install',
        'console.test',
    ];

    protected function getProvidedServiceNames(): array
    {
        return array_merge(
            self::COMMAND_SERVICES,
            [CommandLoaderInterface::class]
        );
    }

    public function register(): void
    {
        $container = $this->getContainer();

        $container->addShared(
            CommandLoaderInterface::class,
            function () use ($container): CommandLoaderInterface {
                $commandServiceNames = self::COMMAND_SERVICES;
                $commandNames = array_map(
                    fn (string $commandServiceName) => substr($commandServiceName, strlen('console.')),
                    $commandServiceNames
                );

                $commandMap = array_combine($commandNames, $commandServiceNames);

                return new ContainerCommandLoader($container, $commandMap);
            }
        );

        $container->addShared(
            'console.apifetcher',
            fn() => new FetchCommand($container->get(Fetcher::class))
        );
        $container->addShared(
            'console.install',
            fn() => new InstallCommand($container->get(DatabaseSchemaInstaller::class))
        );
        $container->addShared(
            'console.test',
            fn() => new TestCommand($container->get(Formatter::class))
        );
    }
}