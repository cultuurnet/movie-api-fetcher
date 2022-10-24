<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Parser;

use CultuurNet\MovieApiFetcher\Date\DateFactoryInterface;
use CultuurNet\MovieApiFetcher\EntryPoster\EntryPosterInterface;
use CultuurNet\MovieApiFetcher\Formatter\FormatterInterface;
use CultuurNet\MovieApiFetcher\Identification\IdentificationFactoryInterface;
use CultuurNet\MovieApiFetcher\Term\TermFactoryInterface;
use CultuurNet\MovieApiFetcher\Theater\TheaterFactoryInterface;
use CultuurNet\MovieApiFetcher\Url\UrlFactoryInterface;
use CultuurNet\TransformEntryStore\Stores\RepositoryInterface;
use CultuurNet\TransformEntryStore\ValueObjects\AgeRange\AgeRange;
use CultuurNet\TransformEntryStore\ValueObjects\Language\LanguageCode;
use Monolog\Logger;
use ValueObjects\Identity\UUID;
use ValueObjects\Number\Integer;

class Parser implements ParserInterface
{
    public const KINEPOLIS_COPYRIGHT = 'Kinepolis';
    public const MOVIE_TYPE_ID = '0.50.6.0.0';
    public const UIV_MOVIE_KEYWORD = 'UiTinVlaanderenFilm';

    private DateFactoryInterface $dateFactory;

    private EntryPosterInterface $entryPoster;

    private FormatterInterface $formatter;

    private IdentificationFactoryInterface $identificationFactory;

    private TermFactoryInterface $termFactory;

    private TheaterFactoryInterface $theaterFactory;

    private UrlFactoryInterface $urlFactory;

    private RepositoryInterface $repository;

    private Logger $logger;

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
     * @param array[] $movie
     */
    public function process($movie, $priceMatrix): void
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
                if ($genre == '619') {
                    $ageFrom = Integer::fromNative(6);
                    $ageTo = Integer::fromNative(99);
                    $ageRange = new AgeRange($ageFrom, $ageTo);
                }
                $cnetId = $this->termFactory->mapTerm($genre);
            }
        }

        if (!isset($length) || empty($length)) {
            $this->logger->log(Logger::WARNING, $title . ' ' . $mid . ' does not have a length. Will set end equal to start');
            $length = 0;
        }

        $filmScreenings = $this->dateFactory->processDates($dates, $length);

        foreach ($filmScreenings as $filmScreeningTheater => $filmVersions) {
            foreach ($filmVersions as $filmVersion => $filmScreening) {
                $externalId = $this->identificationFactory->generateMovieId($mid, $filmScreeningTheater, $filmVersion);
                $location = $this->theaterFactory->mapTheater($filmScreeningTheater);
                if ($filmVersion === '3D' && isset($title) && !empty($title)) {
                    $movieTitle = $title . ' 3D';
                } else {
                    $movieTitle = $title;
                }

                $cdbid = $this->repository->getCdbid($externalId);
                if (isset($cdbid)) {
                    if ($this->repository->getName($externalId) !== $movieTitle) {
                        $this->repository->updateName($externalId, $movieTitle);
                        $this->entryPoster->updateName($cdbid, $movieTitle);
                    }

                    if ($this->repository->getDescription($externalId) !== $description) {
                        $this->repository->updateDescription($externalId, $description);
                        $this->entryPoster->updateDescription($cdbid, $description);
                    }

                    if ($this->repository->getLocationCdbid($externalId) !== $location) {
                        $this->repository->updateLocationCdbid($externalId, $location);
                        $this->entryPoster->updateLocation($cdbid, $location);
                    }

                    $oldCalendar = $this->repository->getCalendar($externalId);
                    foreach ($filmScreening as $day => $hours) {
                        foreach ($hours as $hour) {
                            $timeStart = $hour[0];
                            $timeEnd = $hour[1];
                            $newDate = [];
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

                    $price = $this->getPrice($filmScreeningTheater, $priceMatrix, $length);
                    foreach ($price as $priceName => $amount) {
                        $this->repository->updatePrice(
                            $externalId,
                            $priceName == 'base',
                            $priceName,
                            $amount,
                            'EUR'
                        );
                    }
                    $jsonPrice = $this->formatter->formatPrice($externalId);
                    $this->entryPoster->updatePriceInfo($cdbid, $jsonPrice);

                    $jsonCalendar = $this->formatter->formatCalendar($externalId);
                    $this->entryPoster->updateCalendar($cdbid, $jsonCalendar);
                } elseif (isset($movieTitle) && !empty($movieTitle)) {
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

                    $this->repository->saveName($externalId, $movieTitle);
                    if (isset($description)) {
                        $this->repository->saveDescription($externalId, $description);
                    }
                    $this->repository->saveTypeId($externalId, self::MOVIE_TYPE_ID);
                    if (isset($cnetId)) {
                        $this->repository->saveThemeId($externalId, $cnetId);
                    }
                    if (isset($ageRange)) {
                        $this->repository->saveAgeRange($externalId, $ageRange);
                    }
                    $this->repository->saveLocationCdbid($externalId, $location);

                    $jsonMovie = $this->formatter->formatEvent($externalId);
                    $cdbid = $this->entryPoster->postMovie($jsonMovie);

                    if (isset($cdbid)) {
                        $this->repository->saveCdbid($externalId, $cdbid);
                        $this->entryPoster->publishEvent($cdbid);

                        $mediaId = $this->entryPoster->addMediaObject((string) $image, $movieTitle, $this->getDefaultCopyright());
                        $this->entryPoster->addImage($cdbid, $mediaId);
                        $this->repository->saveImage($externalId, $mediaId, $movieTitle, $this->getDefaultCopyright(), LanguageCode::NL);

                        $this->repository->addLabel($externalId, self::UIV_MOVIE_KEYWORD);
                        $this->entryPoster->addLabel($cdbid, self::UIV_MOVIE_KEYWORD);
                        if (isset($description)) {
                            $this->entryPoster->updateDescription($cdbid, $description);
                        }
                        if (isset($ageRange)) {
                            $this->entryPoster->updateAgeRange($cdbid, $ageRange);
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

                        $this->repository->saveEventProduction($externalId, $externalIdProduction, $cdbid);
                        // temporary workaround till III-2501 is fixed
                        $jsonCalendar = $this->formatter->formatCalendar($externalId);
                        $this->entryPoster->updateCalendar($cdbid, $jsonCalendar);
                        // end of temporary workaround
                    }
                }
            }
        }


        $productionCdbid = $this->repository->getProductionCdbid($externalIdProduction);
        if (isset($productionCdbid)) {
        } else {
            $productionCdbid = UUID::generateAsString();
            $this->repository->saveProductionCdbid($externalIdProduction, new UUID($productionCdbid));
            $this->repository->saveName($externalIdProduction, $title);
            $this->repository->saveDescription($externalIdProduction, $description);
            $this->repository->saveTypeId($externalIdProduction, self::MOVIE_TYPE_ID);
            if (isset($cnetId)) {
                $this->repository->saveThemeId($externalIdProduction, $cnetId);
            }
        }
        $producionJson = $this->formatter->formatProductionJson($externalIdProduction);
        $this->entryPoster->postProduction($producionJson);
    }

    private function getDefaultCopyright(): string
    {
        return self::KINEPOLIS_COPYRIGHT;
    }

    private function getPrice($tid, $priceMatrix, $length)
    {
        $theatrePrices =  $priceMatrix[$tid];

        $moviePrice = [];
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
