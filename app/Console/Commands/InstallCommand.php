<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Console\Commands;

use CultuurNet\MovieApiFetcher\DatabaseSchemaInstaller;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InstallCommand extends Command
{
    protected DatabaseSchemaInstaller $installer;

    public function __construct(DatabaseSchemaInstaller $installer)
    {
        $this->installer = $installer;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('install')
            ->setDescription('Install the application (db schema insertion, etc.)');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->installer->installSchema();

        $output->writeln('Database schema installed.');

        return 0;
    }
}
