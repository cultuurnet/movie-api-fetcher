<?php

use CultuurNet\TransformEntryStore\Auth\AuthServiceProvider;
use CultuurNet\TransformEntryStore\Database\DbServiceProvider;
use CultuurNet\TransformEntryStore\EntryPoster\EntryPosterServiceProvider;
use CultuurNet\TransformEntryStore\Fetcher\FetcherServiceProvider;
use CultuurNet\TransformEntryStore\Formatter\FormatterServiceProvider;
use CultuurNet\TransformEntryStore\Parser\ParserServiceProvider;
use CultuurNet\TransformEntryStore\Repository\RepositoryServiceProvider;
use CultuurNet\TransformEntryStore\Config\ConfigServiceProvider;
use League\Container\Container;
use League\Container\ReflectionContainer;

$container = new Container();
$container->delegate(new ReflectionContainer(true));

if (!isset($appConfigLocation)) {
    $appConfigLocation =  __DIR__;
}

$container->addServiceProvider(new ConfigServiceProvider());
$container->addServiceProvider(new DbServiceProvider());

$container->addServiceProvider(new AuthServiceProvider());

$container->addServiceProvider(new EntryPosterServiceProvider());
$container->addServiceProvider(new RepositoryServiceProvider());
$container->addServiceProvider(new FormatterServiceProvider());
$container->addServiceProvider(new ParserServiceProvider());
$container->addServiceProvider(new FetcherServiceProvider());

return $container;
