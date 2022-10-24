<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\EntryPoster;

use CultuurNet\TransformEntryStore\ValueObjects\AgeRange\AgeRange;
use CultuurNet\TransformEntryStore\ValueObjects\BookingInfo\BookingInfo;
use ValueObjects\Identity\UUID;

interface EntryPosterInterface
{
    public function postMovie(string $jsonMovie): ?UUID;

    public function postProduction(string $jsonProduction): void;

    public function updateEventType(UUID $cdbid, string $type): void;

    public function updateEventTheme(UUID $cdbid, string $theme): void;

    public function updateCalendar(UUID $cdbid, string $calendar): void;

    public function updateLocation(UUID $cdbid, UUID $locationId): void;

    public function updateName(UUID $cdbid, string $name): void;

    public function updateDescription(UUID $cdbid, string $description): void;

    public function addMediaObject($file, string $description, string $copyright): UUID;

    public function addImage(UUID $cdbid, UUID $mediaObjectId): void;

    public function updateImage(UUID $cdbid, UUID $mediaObjectId, string $description, string $copyrightHolder): void;

    public function deleteImage(UUID $cdbid, UUID $mediaObjectId): void;

    public function setMainImage(UUID $cdbid, UUID $mediaObjectId): void;

    public function updateTargetAudience(UUID $cdbid, $audience): void;

    public function updateBookingInfo(UUID $cdbid, BookingInfo $bookingInfo): void;

    public function updateContactInfo(UUID $cdbid, string $contactPoint): void;

    public function addLabel(UUID $cdbid, string $label): void;

    public function deleteLabel(UUID $cdbid, string $label): void;

    public function updateOrganizer(UUID $cdbid, UUID $organizerId): void;

    public function updatePriceInfo(UUID $cdbid, string $priceInfo): void;

    public function updateAgeRange(UUID $cdbid, AgeRange $typicalAgeRange): void;

    public function deleteAgeRange(UUID $cdbid): void;

    public function updateFacilities(UUID $cdbid, string $facilities): void;

    public function publishEvent(UUID $cdbid): void;

    public function linkProduction(string $name, UUID $eventId): void;
}
