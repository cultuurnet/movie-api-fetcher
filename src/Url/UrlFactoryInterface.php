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
     * @param $mid
     * @return Url
     */
    public function generateMovieDetailUrl($mid);

    /**
     * @param $mediaFile
     * @return Url
     */
    public function generateMediaUrl($mediaFile);

    /**
     * @return Url
     */
    public function generateTheatreUrl();
}
