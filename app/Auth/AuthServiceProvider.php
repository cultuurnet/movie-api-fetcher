<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Auth;

use CultuurNet\MovieApiFetcher\Authentication\Authentication;
use CultuurNet\MovieApiFetcher\Url\UrlFactory;
use CultuurNet\TransformEntryStore\Container\AbstractServiceProvider;

final class AuthServiceProvider extends AbstractServiceProvider
{
    protected function getProvidedServiceNames(): array
    {
        return [
            Authentication::class,
        ];
    }

    public function register(): void
    {
        $container = $this->getContainer();

        $container->addShared(
            Authentication::class,
            fn() => new Authentication(
                $container->get(UrlFactory::class)
            )
        );
    }
}