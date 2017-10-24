<?php

namespace CultuurNet\MovieApiFetcher\Parser;

use CultureFeed_Cdb_Item_Production;
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
    public function process($movie)
    {
        $movieData = $movie['movies'][0];

        var_dump($movieData);
//        $this->urlFactory->
//        $dates = $movie['dates'];
//        foreach ($dates as $date => $dateData) {
//            var_dump($date);
//            foreach ($dateData as $info) {
//                $theater = $this->theaterFactory->mapTheater($info['tid']);
//            }
//        }
//        $image = $this->urlFactory->generateMediaUrl($movie['poster']);
//        $nativeImage = (string) $image;
//        $title = $movie['title'];
//        $genres = $movie['genre'];
//        foreach ($genres as $genre) {
//            $mappedGenre = $this->termFactory->mapTerm($genre);
//            var_dump($mappedGenre);
//
//        }
//        $production = new CultureFeed_Cdb_Item_Production();
    }
}
