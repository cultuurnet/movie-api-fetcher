<?php

declare(strict_types=1);

namespace CultuurNet\TransformEntryStore\Stores;

use CultuurNet\TransformEntryStore\ValueObjects\AgeRange\AgeRange;
use CultuurNet\TransformEntryStore\ValueObjects\ContactPoint\ContactPoint;
use CultuurNet\TransformEntryStore\ValueObjects\Language\LanguageCode;
use CultuurNet\TransformEntryStore\ValueObjects\TargetAudience\TargetAudience;
use ValueObjects\Identity\UUID;

final class StoreRepository implements RepositoryInterface
{
    private AgeRangeInterface $ageRangeRepository;

    private CalendarInterface $calendarRepository;

    private ContactPointInterface $contactPointRepository;

    private DescriptionRepositoryInterface $descriptionRepository;

    private EventProductionInterface $eventProductionRepository;

    private ImageInterface $imageRepository;

    private LabelInterface $labelRepository;

    private LocationInterface $locationRepository;

    private NameInterface $nameRepository;

    private OrganizerInterface $organizerRepository;

    private ProductionInterface $productionRepository;

    private PriceInterface $priceRepository;

    private RelationInterface $relationRepository;

    private TargetAudienceInterface $targetAudienceRepository;

    private ThemeRepositoryInterface $themeRepository;

    private TypeRepositoryInterface $typeRepository;

    public function __construct(
        AgeRangeInterface $ageRangeRepository,
        CalendarInterface $calendarRepository,
        ContactPointInterface $contactPointRepository,
        DescriptionRepositoryInterface $descriptionRepository,
        EventProductionInterface $eventProductionRepository,
        ImageInterface $imageRepository,
        LabelInterface $labelRepository,
        LocationInterface $locationRepository,
        NameInterface $nameRepository,
        OrganizerInterface $organizerRepository,
        PriceInterface $priceRepository,
        ProductionInterface $productionRepository,
        RelationInterface $relationRepository,
        TargetAudienceInterface $targetAudienceRepository,
        ThemeRepositoryInterface $themeRepository,
        TypeRepositoryInterface $typeRepository
    ) {
        $this->ageRangeRepository = $ageRangeRepository;
        $this->calendarRepository = $calendarRepository;
        $this->contactPointRepository = $contactPointRepository;
        $this->descriptionRepository = $descriptionRepository;
        $this->eventProductionRepository = $eventProductionRepository;
        $this->imageRepository = $imageRepository;
        $this->labelRepository = $labelRepository;
        $this->locationRepository = $locationRepository;
        $this->nameRepository = $nameRepository;
        $this->organizerRepository = $organizerRepository;
        $this->priceRepository = $priceRepository;
        $this->productionRepository = $productionRepository;
        $this->relationRepository = $relationRepository;
        $this->targetAudienceRepository = $targetAudienceRepository;
        $this->themeRepository = $themeRepository;
        $this->typeRepository = $typeRepository;
    }

    public function getAgeRange(
        string $externalId
    ): ?UUID {
        return $this->ageRangeRepository->getAgeRange($externalId);
    }

    public function saveAgeRange(
        string $externalId,
        AgeRange $ageRange
    ): void {
        $this->ageRangeRepository->saveAgeRange($externalId, $ageRange);
    }

    public function updateAgeRange(
        string $externalId,
        AgeRange $ageRange
    ): void {
        $this->ageRangeRepository->updateAgeRange($externalId, $ageRange);
    }

    public function getCalendar(
        string $externalId
    ): ?array {
        return $this->calendarRepository->getCalendar($externalId);
    }

    public function deleteCalendar(
        string $externalId
    ): void {
        $this->calendarRepository->deleteCalendar($externalId);
    }


    public function saveCalendar(
        string $externalId,
        $date,
        $timeStart,
        $timeEnd
    ) {
        $this->calendarRepository->saveCalendar($externalId, $date, $timeStart, $timeEnd);
    }

    public function getContactPoints(
        string $externalId
    ): ?array {
        return $this->contactPointRepository->getContactPoints($externalId);
    }


    public function saveContactPoint(
        string $externalId,
        ContactPoint $contactPoint
    ): void {
        $this->contactPointRepository->saveContactPoint($externalId, $contactPoint);
    }


    public function updateContactPoint(
        string $externalId,
        ContactPoint $contactPoint
    ): void {
        $this->contactPointRepository->updateContactPoint($externalId, $contactPoint);
    }


    public function getDescription(
        string $externalId
    ): ?string {
        return $this->descriptionRepository->getDescription($externalId);
    }


    public function saveDescription(
        string $externalId,
        string $description
    ): void {
        $this->descriptionRepository->saveDescription($externalId, $description);
    }


    public function updateDescription(
        string $externalId,
        string $description
    ): void {
        $this->descriptionRepository->updateDescription($externalId, $description);
    }


    public function getCdbids(
        string $externalId
    ): ?array {
        return $this->eventProductionRepository->getCdbids($externalId);
    }


    public function saveEventProduction(
        string $externalIdEvent,
        string $externalIdProduction,
        UUID $cdbid
    ): void {
        $this->eventProductionRepository->saveEventProduction($externalIdEvent, $externalIdProduction, $cdbid);
    }


    public function getImageId(
        $externalId
    ): ?string {
        return $this->imageRepository->getImageId($externalId);
    }


    public function saveImage(
        string $externalId,
        UUID $imageId,
        string $description,
        string $copyright,
        LanguageCode $languageCode
    ): void {
        $this->imageRepository->saveImage($externalId, $imageId, $description, $copyright, $languageCode);
    }


    public function updateImage(
        string $externalId,
        UUID $imageId,
        string $description,
        string $copyright,
        LanguageCode $languageCode
    ): void {
        $this->imageRepository->updateImage($externalId, $imageId, $description, $copyright, $languageCode);
    }


    public function addLabel(
        string $externalId,
        string $label
    ): void {
        $this->labelRepository->addLabel($externalId, $label);
    }


    public function deleteLabel(
        string $externalId,
        string $label
    ): void {
        $this->labelRepository->deleteLabel($externalId, $label);
    }


    public function getLocationCdbid(
        string $externalId
    ): ?string {
        return $this->locationRepository->getLocationCdbid($externalId);
    }


    public function saveLocationCdbid(
        string $externalId,
        UUID $locationCdbid
    ): void {
        $this->locationRepository->saveLocationCdbid($externalId, $locationCdbid);
    }


    public function updateLocationCdbid(
        string $externalId,
        UUID $locationCdbid
    ): void {
        $this->locationRepository->updateLocationCdbid($externalId, $locationCdbid);
    }


    public function getName(
        string $externalId
    ): ?string {
        return $this->nameRepository->getName($externalId);
    }


    public function saveName(
        string $externalId,
        string $name
    ) {
        $this->nameRepository->saveName($externalId, $name);
    }


    public function updateName(
        string $externalId,
        string $name
    ) {
        $this->nameRepository->updateName($externalId, $name);
    }


    public function getOrganizerCdbid(
        string $externalId
    ): ?UUID {
        return $this->organizerRepository->getOrganizerCdbid($externalId);
    }


    public function saveOrganizerCdbid(
        string $externalId,
        UUID $organizerCdbid
    ): void {
        $this->organizerRepository->saveOrganizerCdbid($externalId, $organizerCdbid);
    }


    public function updateOrganizerCdbid(
        string $externalId,
        UUID $organizerCdbid
    ): void {
        $this->organizerRepository->updateOrganizerCdbid($externalId, $organizerCdbid);
    }


    public function getPrice(
        string $externalId
    ): ?array {
        return $this->priceRepository->getPrice($externalId);
    }


    public function deletePrice(
        string $externalId
    ): void {
        $this->priceRepository->deletePrice($externalId);
    }


    public function savePrice(
        string $externalId,
        $isBasePrice,
        $name,
        $price,
        $currency
    ): void {
        $this->priceRepository->savePrice($externalId, $isBasePrice, $name, $price, $currency);
    }


    public function updatePrice(
        string $externalId,
        $isBasePrice,
        $name,
        $price,
        $currency
    ): void {
        $this->priceRepository->updatePrice($externalId, $isBasePrice, $name, $price, $currency);
    }


    public function getProductionCdbid(
        string $externalId
    ): ?UUID {
        return $this->productionRepository->getProductionCdbid($externalId);
    }


    public function saveProductionCdbid(
        string $externalId,
        UUID $cdbid
    ): void {
        $this->productionRepository->saveProductionCdbid($externalId, $cdbid);
    }


    public function getCdbid(
        string $externalId
    ): ?UUID {
        return $this->relationRepository->getCdbid($externalId);
    }


    public function getExternalId(
        UUID $cdbid
    ): ?string {
        return $this->relationRepository->getExternalId($cdbid);
    }


    public function saveCdbid(
        string $externalId,
        UUID $cdbid
    ): void {
        $this->relationRepository->saveCdbid($externalId, $cdbid);
    }


    public function getTargetAudience(
        string $externalId
    ): ?TargetAudience {
        return $this->targetAudienceRepository->getTargetAudience($externalId);
    }


    public function saveTargetAudience(
        string $externalId,
        TargetAudience $targetAudience
    ): void {
        $this->targetAudienceRepository->saveTargetAudience($externalId, $targetAudience);
    }


    public function getThemeId(
        string $externalId
    ): ?string {
        return $this->themeRepository->getThemeId($externalId);
    }


    public function saveThemeId(
        string $externalId,
        string $themeId
    ): void {
        $this->themeRepository->saveThemeId($externalId, $themeId);
    }

    public function updateThemeId(
        string $externalId,
        string $themeId
    ): void {
        $this->themeRepository->updateThemeId($externalId, $themeId);
    }

    public function getTypeId(
        string $externalId
    ): ?string {
        return $this->typeRepository->getTypeId($externalId);
    }

    public function saveTypeId(
        string $externalId,
        string $typeId
    ): void {
        $this->typeRepository->saveTypeId($externalId, $typeId);
    }

    public function updateTypeId(
        string $externalId,
        string $typeId
    ): void {
        $this->typeRepository->updateTypeId($externalId, $typeId);
    }
}
