#!/usr/bin/env php
<?php

use CultuurNet\TransformEntryStore\Console\ConsoleServiceProvider;
use League\Container\DefinitionContainerInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\CommandLoader\CommandLoaderInterface;

require_once __DIR__ . '/../vendor/autoload.php';

/** @var DefinitionContainerInterface $container */
$container = require __DIR__ . '/../bootstrap.php';
$container->addServiceProvider(new ConsoleServiceProvider());


$consoleApp = new Application('apifetcher');
$consoleApp->setCommandLoader($container->get(CommandLoaderInterface::class));

/*$consoleApp->add(
    new FetchCommand($app['fetcher'])
);

$consoleApp->add(
    new InstallCommand()
);*/

try {
    $consoleApp->run();
} catch (Exception $e) {
    echo $e->getTraceAsString();
}
