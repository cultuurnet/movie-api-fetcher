<?php

namespace CultuurNet\MovieApiFetcher\Parser;

use CultureFeed_Cdb_Item_Production;
use CultuurNet\MovieApiFetcher\Date\DateFactoryInterface;
use CultuurNet\MovieApiFetcher\Identification\IdentificationFactoryInterface;
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
     * @var IdentificationFactoryInterface
     */
    private $identificationFactory;

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
     * @param IdentificationFactoryInterface $identificationFactory
     * @param TermFactoryInterface $termFactory
     * @param TheaterFactoryInterface $theaterFactory
     * @param UrlFactoryInterface $urlFactory
     */
    public function __construct(
        DateFactoryInterface $dateFactory,
        IdentificationFactoryInterface $identificationFactory,
        TermFactoryInterface $termFactory,
        TheaterFactoryInterface $theaterFactory,
        UrlFactoryInterface $urlFactory
    ) {
        $this->dateFactory = $dateFactory;
        $this->identificationFactory = $identificationFactory;
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
        $mid = $movieData['mid'];
        $externalIdProduction = $this->identificationFactory->generateMovieProductionId($mid);

        $title = $movieData['title'];
        $image = $movieData['poster'];
        $description = $movieData['desc'];
        $dates = $movieData['dates'];
        $length = $movieData['length'];
        $genres = $movieData['genre'];

        $filmScreenings = $this->dateFactory->processDates($dates, $length);

        foreach ($filmScreenings as $filmScreeningTheater => $filmScreening) {
            $externalId=$this->identificationFactory->generateMovieId($mid, $filmScreeningTheater);
            foreach ($filmScreening as $day => $hours) {
                foreach ($hours as $hour) {
                    $start = $hour[0];
                    $end = $hour[1];
                }
            }
        }

        foreach ($genres as $genre) {
            $cnetId = $this->termFactory->mapTerm($genre);
        }

        var_dump($movieData);

        var_dump($movieData);
        //        $production = new CultureFeed_Cdb_Item_Production();

    }
}
