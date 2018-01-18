<?php

namespace CultuurNet\MovieApiFetcher\EntryPoster;

use CultuurNet\TransformEntryStore\ValueObjects\BookingInfo\BookingInfo;
use CultuurNet\UDB3\Calendar;
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
     * @param UUID $cdbid
     * @param StringLiteral $type
     * @return string
     */
    public function updateEventType(UUID $cdbid, StringLiteral $type);

    /**
     * @param UUID $cdbid
     * @param StringLiteral $theme
     * @return string
     */
    public function updateEventTheme(UUID $cdbid, StringLiteral $theme);

    /**
     * @param UUID $cdbid
     * @param Calendar $calendar
     * @return string
     */
    public function updateCalendar(UUID $cdbid, Calendar $calendar);

    /**
     * @param UUID $cdbid
     * @param UUID $locationId
     * @return string
     */
    public function updateLocation(UUID $cdbid, UUID $locationId);

    /**
     * @param UUID $cdbid
     * @param StringLiteral $name
     * @return string
     */
    public function updateName(UUID $cdbid, StringLiteral $name);

    /**
     * @param UUID $cdbid
     * @param StringLiteral $description
     * @return string
     */
    public function updateDescription(UUID $cdbid, StringLiteral $description);

    /**
     * @param $file
     * @param $description
     * @param $copyright
     * @return string
     */
    public function addMediaObject($file, $description, $copyright);

    /**
     * @param UUID $cdbid
     * @param UUID $mediaObjectId
     * @return string
     */
    public function addImage(UUID $cdbid, UUID $mediaObjectId);

    /**
     * @param UUID $cdbid
     * @param UUID $mediaObjectId
     * @param StringLiteral $description
     * @param StringLiteral $copyrightHolder
     * @return string
     */
    public function updateImage(UUID $cdbid, UUID $mediaObjectId, StringLiteral $description, StringLiteral $copyrightHolder);

    /**
     * @param UUID $cdbid
     * @param UUID $mediaObjectId
     * @return string
     */
    public function deleteImage(UUID $cdbid, UUID $mediaObjectId);

    /**
     * @param UUID $cdbid
     * @param UUID $mediaObjectId
     * @return string
     */
    public function setMainImage(UUID $cdbid, UUID $mediaObjectId);

    /**
     * @param UUID $cdbid
     * @param $audience
     * @return string
     */
    public function updateTargetAudience(UUID $cdbid, $audience);

    /**
     * @param UUID $cdbid
     * @param BookingInfo $bookingInfo
     * @return string
     */
    public function updateBookingInfo(UUID $cdbid, BookingInfo $bookingInfo);

    /**
     * @param UUID $cdbid
     * @param $contactPoint
     * @return string
     */
    public function updateContactInfo(UUID $cdbid, $contactPoint);

    /**
     * @param UUID $cdbid
     * @param StringLiteral $label
     * @return string
     */
    public function addLabel(UUID $cdbid, StringLiteral $label);

    /**
     * @param UUID $cdbid
     * @param StringLiteral $label
     * @return string
     */
    public function deleteLabel(UUID $cdbid, StringLiteral $label);

    /**
     * @param UUID $cdbid
     * @param UUID $organizerId
     * @return string
     */
    public function updateOrganizer(UUID $cdbid, UUID $organizerId);

    /**
     * @param UUID $cdbid
     * @param $priceInfo
     * @return string
     */
    public function updatePriceInfo(UUID $cdbid, $priceInfo);

    /**
     * @param UUID $cdbid
     * @param $typicalAgeRange
     * @return string
     */
    public function updateAgeRange(UUID $cdbid, $typicalAgeRange);

    /**
     * @param UUID $cdbid
     * @param $facilities
     * @return string
     */
    public function updateFacilities(UUID $cdbid, $facilities);

    /**
     * @param UUID $cdbid
     * @return string
     */
    public function publishEvent(UUID $cdbid);
}
