<?php

namespace CultuurNet\MovieApiFetcher\EntryPoster;

use ValueObjects\Identity\UUID;
use ValueObjects\StringLiteral\StringLiteral;

interface EntryPosterInterface
{
    /**
     * @param StringLiteral $jsonMovie
     * @return UUID
     */
    public function postMovie(StringLiteral $jsonMovie);

    public function updateEventType(UUID $cdbid, StringLiteral $type);

    public function updateEventTheme(UUID $cdbid, StringLiteral $theme);

    public function updateCalendar();

    public function updateLocation();

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

    // TODO: Put image stuff here

    /**
     * @param UUID $cdbid
     * @param $audience
     * @return string
     */
    public function updateTargetAudience(UUID $cdbid, $audience);

    /**
     * @param UUID $cdbid
     * @param $bookingInfo
     * @return string
     */
    public function updateBookingInfo(UUID $cdbid, $bookingInfo);

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
     * @return mixed
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
