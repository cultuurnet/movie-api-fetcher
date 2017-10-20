<?php

namespace CultuurNet\MovieApiFetcher\Parser;

use CultuurNet\MovieApiFetcher\Term\TermFactoryInterface;
use CultuurNet\MovieApiFetcher\Theater\TheaterFactoryInterface;
use CultuurNet\MovieApiFetcher\Url\UrlFactoryInterface;

class Parser implements ParserInterface
{
    /**
     * @var UrlFactoryInterface
     */
    private $urlFactory;

    /**
     * @var TermFactoryInterface
     */
    private $termFactory;

    /**
     * @var TheaterFactoryInterface
     */
    private $theaterFactory;

    /**
     * Parser constructor.
     * @param UrlFactoryInterface $urlFactory
     * @param TermFactoryInterface $termFactory
     * @param TheaterFactoryInterface $theaterFactory
     */
    public function __construct(
        UrlFactoryInterface $urlFactory,
        TermFactoryInterface $termFactory,
        TheaterFactoryInterface $theaterFactory
    ) {
        $this->urlFactory = $urlFactory;
        $this->termFactory = $termFactory;
        $this->theaterFactory = $theaterFactory;
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
    public function process($movie)
    {
        $id = $movie['mid'];
        $dates = $movie['dates'];
        $image = $this->urlFactory->generateMediaUrl($movie['poster']);
        $nativeImage = (string) $image;
        $title = $movie['title'];
        $genres = $movie['genre'];
        foreach ($genres as $genre) {
            $mappedGenre = $this->termFactory->mapTerm($genre);
            var_dump($mappedGenre);

        }
    }
}
