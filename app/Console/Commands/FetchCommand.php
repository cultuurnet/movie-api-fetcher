<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Console\Commands;

use CultuurNet\MovieApiFetcher\Fetcher\FetcherInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchCommand extends Command
{
    protected FetcherInterface $fetcher;

    public function __construct(FetcherInterface $fetcher)
    {
        $this->fetcher = $fetcher;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('apifetcher')
            ->setDescription('Start querying the Kinepolis API.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->fetcher->start();
        return 0;
    }
}
