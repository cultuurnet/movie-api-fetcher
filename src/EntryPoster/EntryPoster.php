<?php

namespace CultuurNet\MovieApiFetcher\EntryPoster;

use CultuurNet\TransformEntryStore\ValueObjects\BookingInfo\BookingInfo;
use CultuurNet\UDB3\Calendar;
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
        $uri = (string) $this->url . 'events/';

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
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/name/nl';

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
        $commandId =  $resp['commandId'];

        return $commandId;
    }

    /**
     * @inheritdoc
     */
    public function updateDescription(UUID $cdbid, StringLiteral $description)
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/description/nl';

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
        $commandId =  $resp['commandId'];

        return $commandId;
    }

    /**
     * EntryPoster constructor.
     * @param $token
     * @param $apiKey
     * @param $url
     */
    public function __construct($token, $apiKey, $url)
    {
        $this->token = $token;
        $this->apiKey = $apiKey;
        $this->url = $url;
    }

    /**
     * @inheritdoc
     */
    public function updateEventType(UUID $cdbid, StringLiteral $type)
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/type/' . $type->toNative();

        $request = $client->put(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $commandId =  $resp['commandId'];

        return $commandId;
    }

    /**
     * @inheritdoc
     */
    public function updateEventTheme(UUID $cdbid, StringLiteral $theme)
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/theme/' . $theme->toNative();

        $request = $client->put(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $commandId =  $resp['commandId'];

        return $commandId;
    }

    /**
     * @inheritdoc
     */
    public function updateCalendar(Calendar $calendar)
    {
        // TODO: Implement updateCalendar() method.
    }

    /**
     * @inheritdoc
     */
    public function updateLocation(UUID $cdbid, UUID $locationId)
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/location/' . $locationId->toNative();

        $request = $client->put(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $commandId =  $resp['commandId'];

        return $commandId;
    }

    /**
     * @inheritdoc
     */
    public function addMediaObject($file, $description, $copyright)
    {
        $client = new Client();
        $uri = (string) $this->url . 'images/';

        $request = $client->post(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $request->setBody('{ "file": "' . $file . '", "description": "' . $description . '", "copyrightHolder": "' . $copyright . '", "language": "nl" }');
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $imageId =  $resp['imageId'];

        return $imageId;
    }

    /**
     * @inheritdoc
     */
    public function updateTargetAudience(UUID $cdbid, $audience)
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/audience';

        $request = $client->put(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $request->setBody('{ "audience": "' . $audience . '" }');
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $commandId =  $resp['commandId'];

        return $commandId;
    }

    /**
     * @inheritdoc
     */
    public function updateBookingInfo(UUID $cdbid, BookingInfo $bookingInfo)
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/bookingInfo';

        $request = $client->put(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $request->setBody(
            '{' .
                '"bookingInfo": {' .
                '"url": "' . $bookingInfo . '",' .
                '"urlLabel": "' . $bookingInfo . '",' .
                '"email": "' . $bookingInfo . '",' .
                '"phone": "' . $bookingInfo . '",' .
                '"availabilityStarts": "' . $bookingInfo . '",' .
                '"availabilityEnds": "' . $bookingInfo . '"' .
                '}'.
                '}'
        );
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $commandId =  $resp['commandId'];

        return $commandId;
    }

    /**
     * @inheritdoc
     */
    public function updateContactInfo(UUID $cdbid, $contactPoint)
    {
        // TODO: Implement updateContactInfo() method.
    }

    /**
     * @inheritdoc
     */
    public function addLabel(UUID $cdbid, StringLiteral $label)
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/labels/' . $label->toNative();

        $request = $client->put(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $commandId =  $resp['commandId'];

        return $commandId;
    }

    /**
     * @inheritdoc
     */
    public function deleteLabel(UUID $cdbid, StringLiteral $label)
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/labels/' . $label->toNative();

        $request = $client->delete(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $commandId =  $resp['commandId'];

        return $commandId;
    }

    /**
     * @inheritdocc
     */
    public function updateOrganizer(UUID $cdbid, UUID $organizerId)
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/organizer/' . $organizerId->toNative();

        $request = $client->put(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $commandId =  $resp['commandId'];

        return $commandId;
    }

    /**
     * @inheritdoc
     */
    public function updatePriceInfo(UUID $cdbid, $priceInfo)
    {
        // TODO: Implement updatePriceInfo() method.
    }

    /**
     * @inheritdoc
     */
    public function updateAgeRange(UUID $cdbid, $typicalAgeRange)
    {
        // TODO: Implement updateAgeRange() method.
    }

    /**
     * @inheritdoc
     */
    public function updateFacilities(UUID $cdbid, $facilities)
    {
        // TODO: Implement updateFacilities() method.
    }

    /**
     * @inheritdoc
     */
    public function publishEvent(UUID $cdbid)
    {
        $client = new Client();
        $uri = (string) $this->url . 'event/' . $cdbid->toNative();

        $request = $client->patch(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
                'content-type' => 'application/ld+json;domain-model=Publish',
            ],
            []
        );

        $response = $request->send();
        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $commandId =  $resp['commandId'];

        return $commandId;
    }
}
