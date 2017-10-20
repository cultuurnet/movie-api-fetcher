<?php

namespace CultuurNet\MovieApiFetcher\Authentication;

use ValueObjects\StringLiteral\StringLiteral;

interface AuthenticationInterface
{
    /**
     * @param StringLiteral $key
     * @param StringLiteral $secret
     * @return StringLiteral
     */
    public function getToken(StringLiteral $key, StringLiteral $secret);
}
