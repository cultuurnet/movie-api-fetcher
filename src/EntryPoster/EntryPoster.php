<?php

namespace CultuurNet\MovieApiFetcher\EntryPoster;

use CultuurNet\MovieApiFetcher\Url\UrlFactoryInterface;
use Guzzle\Http\Client;
use ValueObjects\Identity\UUID;
use ValueObjects\StringLiteral\StringLiteral;

class EntryPoster implements EntryPosterInterface
{
    private $token;

    private $apiKey;

    private $url;

    /**
     * @inheritdoc
     */
    public function postMovie(StringLiteral $jsonMovie)
    {
        $client = new Client();
        $uri = (string) $this->url;

        $request = $client->post(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $request->setBody($jsonMovie->toNative());
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $cdbid =  $resp['eventId'];

        return new UUID($cdbid);
    }

    /**
 * @inheritdoc
 */
    public function updateName(UUID $cdbid, StringLiteral $name)
    {
        $client = new Client();
        $uri = (string) $this->url;

        $request = $client->put(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $request->setBody('{ "name": "' . $name . '" }');
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $cdbid =  $resp['eventId'];

        return new UUID($cdbid);
    }

    /**
     * @inheritdoc
     */
    public function updateDescription(UUID $cdbid, StringLiteral $description)
    {
        $client = new Client();
        $uri = (string) $this->url;

        $request = $client->put(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $request->setBody('{ "description": "' . $description . '" }');
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $cdbid =  $resp['eventId'];

        return new UUID($cdbid);
    }

    public function __construct($token, $apiKey, $url)
    {
        $this->token = $token;
        $this->apiKey = $apiKey;
        $this->url = $url;
    }

    public function updateEventType(UUID $cdbid, StringLiteral $type)
    {
        // TODO: Implement updateEventType() method.
    }

    public function updateEventTheme(UUID $cdbid, StringLiteral $theme)
    {
        // TODO: Implement updateEventTheme() method.
    }

    public function updateCalendar()
    {
        // TODO: Implement updateCalendar() method.
    }

    public function updateLocation()
    {
        // TODO: Implement updateLocation() method.
    }

    /**
     * @param UUID $cdbid
     * @param $audience
     * @return string
     */
    public function updateTargetAudience(UUID $cdbid, $audience)
    {
        // TODO: Implement updateTargetAudience() method.
    }

    /**
     * @param UUID $cdbid
     * @param $bookingInfo
     * @return string
     */
    public function updateBookingInfo(UUID $cdbid, $bookingInfo)
    {
        // TODO: Implement updateBookingInfo() method.
    }

    /**
     * @param UUID $cdbid
     * @param $contactPoint
     * @return string
     */
    public function updateContactInfo(UUID $cdbid, $contactPoint)
    {
        // TODO: Implement updateContactInfo() method.
    }

    /**
     * @param UUID $cdbid
     * @param StringLiteral $label
     * @return string
     */
    public function addLabel(UUID $cdbid, StringLiteral $label)
    {
        // TODO: Implement addLabel() method.
    }

    /**
     * @param UUID $cdbid
     * @param StringLiteral $label
     * @return string
     */
    public function deleteLabel(UUID $cdbid, StringLiteral $label)
    {
        // TODO: Implement deleteLabel() method.
    }

    /**
     * @param UUID $cdbid
     * @param UUID $organizerId
     * @return string
     */
    public function updateOrganizer(UUID $cdbid, UUID $organizerId)
    {
        // TODO: Implement updateOrganizer() method.
    }

    /**
     * @param UUID $cdbid
     * @param $priceInfo
     * @return mixed
     */
    public function updatePriceInfo(UUID $cdbid, $priceInfo)
    {
        // TODO: Implement updatePriceInfo() method.
    }

    /**
     * @param UUID $cdbid
     * @param $typicalAgeRange
     * @return string
     */
    public function updateAgeRange(UUID $cdbid, $typicalAgeRange)
    {
        // TODO: Implement updateAgeRange() method.
    }

    /**
     * @param UUID $cdbid
     * @param $facilities
     * @return string
     */
    public function updateFacilities(UUID $cdbid, $facilities)
    {
        // TODO: Implement updateFacilities() method.
    }

    /**
     * @param UUID $cdbid
     * @return string
     */
    public function publishEvent(UUID $cdbid)
    {
        // TODO: Implement publishEvent() method.
    }
}
