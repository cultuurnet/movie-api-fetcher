<?php

namespace CultuurNet\MovieApiFetcher\Url;

use ValueObjects\StringLiteral\StringLiteral;
use ValueObjects\Web\Url;

class UrlFactory implements UrlFactoryInterface
{
    /**
     * @var StringLiteral
     */
    private $baseUrl;

    /**
     * UrlFactory constructor.
     * @param StringLiteral $baseUrl
     */
    public function __construct(StringLiteral $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @inheritdoc
     */
    public function generateTokenUrl()
    {
        return Url::fromNative($this->baseUrl->toNative() . 'jwt/1.0/token');
    }

    /**
     * @inheritdoc
     */
    public function generateMoviesUrl()
    {
        return Url::fromNative($this->baseUrl->toNative() . 'content/1.0/movies');
    }
}
