<?php

namespace CultuurNet\MovieApiFetcher\EntryPoster;

use CultuurNet\TransformEntryStore\ValueObjects\AgeRange\AgeRange;
use CultuurNet\TransformEntryStore\ValueObjects\BookingInfo\BookingInfo;
use ValueObjects\Identity\UUID;
use ValueObjects\StringLiteral\StringLiteral;

interface EntryPosterInterface
{
    /**
     * @param StringLiteral $jsonMovie
     * @return UUID
     */
    public function postMovie(StringLiteral $jsonMovie);

    /**
     * @param StringLiteral $jsonProduction
     * @return UUID
     */
    public function postProduction(StringLiteral $jsonProduction);

    /**
     * @param UUID $cdbid
     * @param StringLiteral $type
     * @return void
     */
    public function updateEventType(UUID $cdbid, StringLiteral $type);

    /**
     * @param UUID $cdbid
     * @param StringLiteral $theme
     * @return void
     */
    public function updateEventTheme(UUID $cdbid, StringLiteral $theme);

    /**
     * @param UUID $cdbid
     * @param StringLiteral $calendar
     * @return void
     */
    public function updateCalendar(UUID $cdbid, StringLiteral $calendar);

    /**
     * @param UUID $cdbid
     * @param UUID $locationId
     * @return void
     */
    public function updateLocation(UUID $cdbid, UUID $locationId);

    /**
     * @param UUID $cdbid
     * @param StringLiteral $name
     * @return void
     */
    public function updateName(UUID $cdbid, StringLiteral $name);

    /**
     * @param UUID $cdbid
     * @param StringLiteral $description
     * @return void
     */
    public function updateDescription(UUID $cdbid, StringLiteral $description);

    /**
     * @param $file
     * @param StringLiteral $description
     * @param StringLiteral $copyright
     * @return UUID
     */
    public function addMediaObject($file, StringLiteral $description, StringLiteral $copyright);

    /**
     * @param UUID $cdbid
     * @param UUID $mediaObjectId
     * @return void
     */
    public function addImage(UUID $cdbid, UUID $mediaObjectId);

    /**
     * @param UUID $cdbid
     * @param UUID $mediaObjectId
     * @param StringLiteral $description
     * @param StringLiteral $copyrightHolder
     * @return void
     */
    public function updateImage(UUID $cdbid, UUID $mediaObjectId, StringLiteral $description, StringLiteral $copyrightHolder);

    /**
     * @param UUID $cdbid
     * @param UUID $mediaObjectId
     * @return void
     */
    public function deleteImage(UUID $cdbid, UUID $mediaObjectId);

    /**
     * @param UUID $cdbid
     * @param UUID $mediaObjectId
     * @return void
     */
    public function setMainImage(UUID $cdbid, UUID $mediaObjectId);

    /**
     * @param UUID $cdbid
     * @param $audience
     * @return void
     */
    public function updateTargetAudience(UUID $cdbid, $audience);

    /**
     * @param UUID $cdbid
     * @param BookingInfo $bookingInfo
     * @return void
     */
    public function updateBookingInfo(UUID $cdbid, BookingInfo $bookingInfo);

    /**
     * @param UUID $cdbid
     * @param StringLiteral $contactPoint
     * @return void
     */
    public function updateContactInfo(UUID $cdbid, StringLiteral $contactPoint);

    /**
     * @param UUID $cdbid
     * @param StringLiteral $label
     * @return void
     */
    public function addLabel(UUID $cdbid, StringLiteral $label);

    /**
     * @param UUID $cdbid
     * @param StringLiteral $label
     * @return void
     */
    public function deleteLabel(UUID $cdbid, StringLiteral $label);

    /**
     * @param UUID $cdbid
     * @param UUID $organizerId
     * @return void
     */
    public function updateOrganizer(UUID $cdbid, UUID $organizerId);

    /**
     * @param UUID $cdbid
     * @param StringLiteral $priceInfo
     * @return void
     */
    public function updatePriceInfo(UUID $cdbid, StringLiteral $priceInfo);

    /**
     * @param UUID $cdbid
     * @param AgeRange $typicalAgeRange
     * @return void
     */
    public function updateAgeRange(UUID $cdbid, AgeRange $typicalAgeRange);

    /**
     * @param UUID $cdbid
     * @return void
     */
    public function deleteAgeRange(UUID $cdbid);

    /**
     * @param UUID $cdbid
     * @param StringLiteral $facilities
     * @return void
     */
    public function updateFacilities(UUID $cdbid, StringLiteral $facilities);

    /**
     * @param UUID $cdbid
     * @return void
     */
    public function publishEvent(UUID $cdbid);

    /**
     * @param StringLiteral $name
     * @param UUID $eventId
     * @return void
     */
    public function linkProduction(StringLiteral $name, UUID $eventId);
}
