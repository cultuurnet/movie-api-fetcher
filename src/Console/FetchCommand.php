<?php

namespace CultuurNet\MovieApiFetcher\Console;

use CultuurNet\MovieApiFetcher\Fetcher\FetcherInterface;
use Knp\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FetchCommand extends Command
{
    /**
     * @var FetcherInterface
     */
    protected $fetcher;

    /**
     * FetchCommand constructor.
     * @param FetcherInterface $fetcher
     */
    public function __construct(FetcherInterface $fetcher)
    {
        $this->fetcher = $fetcher;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('apifetcher')
            ->setDescription('Start querying the Kinepolis API.');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
            $this->fetcher->start();
    }
}
