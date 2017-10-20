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
        return Url::fromNative($this->baseUrl->toNative() . 'services/jwt/1.0/token');
    }

    /**
     * @inheritdoc
     */
    public function generateMoviesUrl()
    {
        return Url::fromNative($this->baseUrl->toNative() . 'services/content/1.0/movies?progList=2');
    }

    /**
     * @inheritdoc
     */
    public function generateMediaUrl($mediaFile)
    {
        return Url::fromNative($this->baseUrl->toNative() .'sites/kinepolis.be.nl/files' . $mediaFile);
    }
}
