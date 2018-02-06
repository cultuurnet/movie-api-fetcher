<?php

namespace CultuurNet\MovieApiFetcher\Parser;

use CultureFeed_Cdb_Item_Production;
use CultuurNet\MovieApiFetcher\Date\DateFactoryInterface;
use CultuurNet\MovieApiFetcher\EntryPoster\EntryPosterInterface;
use CultuurNet\MovieApiFetcher\Formatter\FormatterInterface;
use CultuurNet\MovieApiFetcher\Identification\IdentificationFactoryInterface;
use CultuurNet\MovieApiFetcher\Term\TermFactoryInterface;
use CultuurNet\MovieApiFetcher\Theater\TheaterFactoryInterface;
use CultuurNet\MovieApiFetcher\Url\UrlFactoryInterface;
use CultuurNet\TransformEntryStore\Stores\RepositoryInterface;
use CultuurNet\TransformEntryStore\ValueObjects\Language\LanguageCode;
use Monolog\Logger;
use ValueObjects\Identity\UUID;
use ValueObjects\StringLiteral\StringLiteral;

class Parser implements ParserInterface
{
    const KINEPOLIS_COPYRIGHT = 'Kinepolis';
    const MOVIE_TYPE_ID = '0.50.6.0.0';
    const UIV_MOVIE_KEYWORD = 'UiTinVlaanderenFilm';

    /**
     * @var DateFactoryInterface
     */
    private $dateFactory;

    /**
     * @var EntryPosterInterface
     */
    private $entryPoster;

    /**
     * @var FormatterInterface
     */
    private $formatter;

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
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var
     */
    private $logger;

    /**
     * Parser constructor.
     * @param DateFactoryInterface $dateFactory
     * @param EntryPosterInterface $entryPoster
     * @param FormatterInterface $formatter
     * @param IdentificationFactoryInterface $identificationFactory
     * @param TermFactoryInterface $termFactory
     * @param TheaterFactoryInterface $theaterFactory
     * @param UrlFactoryInterface $urlFactory
     * @param RepositoryInterface $repository
     * @param Logger $logger
     */
    public function __construct(
        DateFactoryInterface $dateFactory,
        EntryPosterInterface $entryPoster,
        FormatterInterface $formatter,
        IdentificationFactoryInterface $identificationFactory,
        TermFactoryInterface $termFactory,
        TheaterFactoryInterface $theaterFactory,
        UrlFactoryInterface $urlFactory,
        RepositoryInterface $repository,
        Logger $logger
    ) {
        $this->dateFactory = $dateFactory;
        $this->entryPoster = $entryPoster;
        $this->formatter = $formatter;
        $this->identificationFactory = $identificationFactory;
        $this->termFactory = $termFactory;
        $this->theaterFactory = $theaterFactory;
        $this->urlFactory = $urlFactory;
        $this->repository = $repository;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function process($movie, $priceMatrix)
    {
        $movieData = $movie['movies'][0];
        $mid = $movieData['mid'];
        $externalIdProduction = $this->identificationFactory->generateMovieProductionId($mid);

        $title = $movieData['title'];
        $image = $this->urlFactory->generateMediaUrl($movieData['poster']);
        $description = $movieData['desc'];

        $dates = $movieData['dates'];
        $length = $movieData['length'];
        $genres = $movieData['genre'];

        if (isset($genres)) {
            foreach ($genres as $genre) {
                $cnetId = $this->termFactory->mapTerm($genre);
            }
        }

        $filmScreenings = $this->dateFactory->processDates($dates, $length);

        foreach ($filmScreenings as $filmScreeningTheater => $filmScreening) {
            $externalId = $this->identificationFactory->generateMovieId($mid, $filmScreeningTheater);
            $location = $this->theaterFactory->mapTheater($filmScreeningTheater);

            $cdbid = $this->repository->getCdbid($externalId);
            if (isset($cdbid)) {
                if ($this->repository->getName($externalId) != $title) {
                    $this->repository->updateName($externalId, new StringLiteral($title));
                    $this->entryPoster->updateName($cdbid, new StringLiteral($title));
                }

                if ($this->repository->getDescription($externalId) != $description) {
                    $this->repository->updateDescription($externalId, new StringLiteral($description));
                    $this->entryPoster->updateDescription($cdbid, new StringLiteral($description));
                }

                if ($this->repository->getLocationCdbid($externalId) != $location) {
                    $this->repository->updateLocationCdbid($externalId, $location);
                    $this->entryPoster->updateLocation($cdbid, $location);
                }

                $oldCalendar = $this->repository->getCalendar($externalId);
                foreach ($filmScreening as $day => $hours) {
                    foreach ($hours as $hour) {
                        $timeStart = $hour[0];
                        $timeEnd = $hour[1];
                        $newDate = array();
                        $newDate['date'] = $day;
                        $newDate['time_start'] = $timeStart;
                        $newDate['time_end'] = $timeEnd;
                        if (!in_array($newDate, $oldCalendar)) {
                            $this->repository->saveCalendar(
                                $externalId,
                                $day,
                                $timeStart,
                                $timeEnd
                            );
                        }
                    }
                }

                $jsonCalendar = $this->formatter->formatCalendar($externalId);
                $this->entryPoster->updateCalendar($cdbid, $jsonCalendar);
            } elseif (isset($title) && !empty($title)) {
                foreach ($filmScreening as $day => $hours) {
                    foreach ($hours as $hour) {
                        $timeStart = $hour[0];
                        $timeEnd = $hour[1];
                        $this->repository->saveCalendar(
                            $externalId,
                            $day,
                            $timeStart,
                            $timeEnd
                        );
                    }
                }

                $this->repository->saveName($externalId, new StringLiteral($title));
                if (isset($description)) {
                    $this->repository->saveDescription($externalId, new StringLiteral($description));
                }
                $this->repository->saveTypeId($externalId, new StringLiteral(PARSER::MOVIE_TYPE_ID));
                if (isset($cnetId)) {
                    $this->repository->saveThemeId($externalId, new StringLiteral($cnetId));
                }
                $this->repository->saveLocationCdbid($externalId, $location);

                $jsonMovie = $this->formatter->formatEvent($externalId);
                $cdbid = $this->entryPoster->postMovie($jsonMovie);

                if (isset($cdbid)) {
                    $this->repository->saveCdbid($externalId, $cdbid);
                    $this->entryPoster->publishEvent($cdbid);

                    $mediaId = $this->entryPoster->addMediaObject((string) $image, new StringLiteral($title), $this->getDefaultCopyright());
                    $this->entryPoster->addImage($cdbid, $mediaId);
                    $this->repository->saveImage($externalId, $mediaId, new StringLiteral($title), $this->getDefaultCopyright(), LanguageCode::NL());

                    $this->repository->addLabel($externalId, new StringLiteral(Parser::UIV_MOVIE_KEYWORD));
                    $this->entryPoster->addLabel($cdbid, new StringLiteral(Parser::UIV_MOVIE_KEYWORD));
                    if (isset($description)) {
                        $this->entryPoster->updateDescription($cdbid, new StringLiteral($description));
                    }

                    $price = $this->getPrice($filmScreeningTheater, $priceMatrix, $length);
                    foreach ($price as $priceName => $amount) {
                        $this->repository->savePrice(
                            $externalId,
                            $priceName == 'base',
                            $priceName,
                            $amount,
                            'EUR'
                        );
                    }
                    $jsonPrice = $this->formatter->formatPrice($externalId);
                    $this->entryPoster->updatePriceInfo($cdbid, $jsonPrice);
                }
            }
        }
    }

    private function getDefaultCopyright()
    {
        return new StringLiteral(Parser::KINEPOLIS_COPYRIGHT);
    }

    private function getPrice($tid, $priceMatrix, $length)
    {
        $theatrePrices =  $priceMatrix[$tid];

        $moviePrice = array();
        if ($length >= 135 && isset($theatrePrices['long_movies'])) {
            $moviePrice['base'] = $theatrePrices['base'] + $theatrePrices['long_movies'];
            $moviePrice['Kortingstarief'] = $theatrePrices['Kortingstarief'] + $theatrePrices['long_movies'];
            $moviePrice['Kinepolis Student Card'] = $theatrePrices['Kinepolis Student Card'] + $theatrePrices['long_movies'];
        } else {
            $moviePrice['base'] = $theatrePrices['base'];
            $moviePrice['Kortingstarief'] = $theatrePrices['Kortingstarief'];
            $moviePrice['Kinepolis Student Card'] = $theatrePrices['Kinepolis Student Card'];
        }
        return $moviePrice;
    }
}
