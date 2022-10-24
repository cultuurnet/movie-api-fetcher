<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Console;

use CultuurNet\MovieApiFetcher\DatabaseSchemaInstaller;
use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setName('install')
            ->setDescription('Install the application (db schema insertion, etc.)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->getDatabaseSchemaInstaller()->installSchema();

        $output->writeln('Database schema installed.');

        return 0;
    }

    protected function getDatabaseSchemaInstaller(): DatabaseSchemaInstaller
    {
        $app = $this->getSilexApplication();
        return $app['database.installer'];
    }
}
