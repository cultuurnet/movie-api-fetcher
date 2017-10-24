<?php

namespace CultuurNet\MovieApiFetcher\Parser;

use CultureFeed_Cdb_Item_Production;
use CultuurNet\MovieApiFetcher\Date\DateFactoryInterface;
use CultuurNet\MovieApiFetcher\Term\TermFactoryInterface;
use CultuurNet\MovieApiFetcher\Theater\TheaterFactoryInterface;
use CultuurNet\MovieApiFetcher\Url\UrlFactoryInterface;

class Parser implements ParserInterface
{
    /**
     * @var DateFactoryInterface
     */
    private $dateFactory;

    /**
     * @var TermFactoryInterface
     */
    private $termFactory;

    /**
     * @var TheaterFactoryInterface
     */
    private $theaterFactory;

    /**
     * @var UrlFactoryInterface
     */
    private $urlFactory;

    /**
     * Parser constructor.
     * @param DateFactoryInterface $dateFactory
     * @param TermFactoryInterface $termFactory
     * @param TheaterFactoryInterface $theaterFactory
     * @param UrlFactoryInterface $urlFactory
     */
    public function __construct(
        DateFactoryInterface $dateFactory,
        TermFactoryInterface $termFactory,
        TheaterFactoryInterface $theaterFactory,
        UrlFactoryInterface $urlFactory
    ) {
        $this->dateFactory =$dateFactory;
        $this->termFactory = $termFactory;
        $this->theaterFactory = $theaterFactory;
        $this->urlFactory = $urlFactory;
    }

    /**
     * @inheritdoc
     */
    public function process($movie)
    {
        $movieData = $movie['movies'][0];

        $title = $movieData['title'];
        $image = $movieData['poster'];
        $description = $movieData['desc'];
        $dates = $movieData['dates'];
        $genres = $movieData['genre'];

        $this->dateFactory->processDates($dates);

        foreach ($genres as $genre) {
            $cnetId = $this->termFactory->mapTerm($genre);
        }



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
