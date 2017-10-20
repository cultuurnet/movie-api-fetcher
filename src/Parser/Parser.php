<?php

namespace CultuurNet\MovieApiFetcher\Parser;

use CultuurNet\MovieApiFetcher\Url\UrlFactoryInterface;

class Parser implements ParserInterface
{
    /**
     * @var UrlFactoryInterface
     */
    private $urlFactory;

    /**
     * Parser constructor.
     * @param UrlFactoryInterface $urlFactory
     */
    public function __construct(UrlFactoryInterface $urlFactory)
    {
        $this->urlFactory = $urlFactory;
    }

    /**
     * @inheritdoc
     */
    public function split($movies)
    {
       foreach ($movies as $movie) {
           $this->process($movie);
       }
    }

    /**
     * @inheritdoc
     */
    public function process($movie) {
        $id = $movie['mid'];
        $dates = $movie['dates'];
        $image = $this->urlFactory->generateMediaUrl($movie['poster']);
        $nativeImage = (string) $image;
        $title = $movie['title'];
    }
}
