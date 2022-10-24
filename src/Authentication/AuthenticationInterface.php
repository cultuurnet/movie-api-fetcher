<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Authentication;

interface AuthenticationInterface
{
    public function getToken(string $key, string $secret): string;
}
