<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Url;

use ValueObjects\Web\Url;

class UrlFactory implements UrlFactoryInterface
{
    private string $baseUrl;

    /**
     * UrlFactory constructor.
     */
    public function __construct(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    /**
     * @inheritdoc
     */
    public function generateTokenUrl()
    {
        return Url::fromNative($this->baseUrl . 'services/jwt/1.0/token');
    }

    /**
     * @inheritdoc
     */
    public function generateMoviesUrl()
    {
        return Url::fromNative($this->baseUrl . 'services/content/1.1/movies?progList=2');
    }

    /**
     * @inheritdoc
     */
    public function generateMovieDetailUrl($mid)
    {
        return Url::fromNative($this->baseUrl . 'services/content/1.1/movies/' . $mid);
    }

    /**
     * @inheritdoc
     */
    public function generateMediaUrl($mediaFile)
    {
        return Url::fromNative($this->baseUrl . 'sites/kinepolis.be.nl/files' . $mediaFile);
    }

    public function generateTheatreUrl()
    {
        return Url::fromNative($this->baseUrl . 'services/content/1.1/theaters');
    }
}
