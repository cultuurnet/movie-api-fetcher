<?php

namespace CultuurNet\MovieApiFetcher\Url;

use ValueObjects\Web\Url;

interface UrlFactoryInterface
{
    /**
     * @return Url
     */
    public function generateTokenUrl();

    /**
     * @return Url
     */
    public function generateMoviesUrl();

    /**
     * @param $mediaFile
     * @return Url
     */
    public function generateMediaUrl($mediaFile);
}
