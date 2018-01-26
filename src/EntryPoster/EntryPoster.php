<?php

namespace CultuurNet\MovieApiFetcher\EntryPoster;

use CultuurNet\TransformEntryStore\ValueObjects\BookingInfo\BookingInfo;
use CultuurNet\UDB3\Calendar;
use Guzzle\Http\Client;
use Monolog\Logger;
use ValueObjects\Identity\UUID;
use ValueObjects\StringLiteral\StringLiteral;

class EntryPoster implements EntryPosterInterface
{
    private $token;

    private $apiKey;

    private $url;

    private $filesFolder;

    /**
     * @var Logger
     */
    private $logger;

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

        try {
            $response = $request->send();
        } catch (\Exception $e) {
            $this->logger->log(Logger::ERROR, 'Failed to post movie, message:  ' . $e->getMessage());
        }

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $cdbid =  $resp['eventId'];
        $this->logger->log(Logger::DEBUG, 'Posted movie ' . $cdbid->toNative());

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
        $this->logger->log(Logger::DEBUG, 'Updated name for ' . $cdbid->toNative() . '. commandId is ' . $commandId);

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
        $this->logger->log(Logger::DEBUG, 'Updated description for ' . $cdbid->toNative() . '. commandId is ' . $commandId);

        return $commandId;
    }

    /**
     * EntryPoster constructor.
     * @param $token
     * @param $apiKey
     * @param $url
     * @param $filesFolder
     * @param Logger $logger
     */
    public function __construct($token, $apiKey, $url, $filesFolder, $logger)
    {
        $this->token = $token;
        $this->apiKey = $apiKey;
        $this->url = $url;
        $this->filesFolder = $filesFolder;
        $this->logger = $logger;
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
        $this->logger->log(Logger::DEBUG, 'Updated eventType for ' . $cdbid->toNative() . '. commandId is ' . $commandId);

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
        $this->logger->log(Logger::DEBUG, 'Updated eventTheme for ' . $cdbid->toNative() . '. commandId is ' . $commandId);

        return $commandId;
    }

    /**
     * @inheritdoc
     */
    public function updateCalendar(UUID $cdbid, Calendar $calendar)
    {
        // TODO: Implement updateCalendar() method.

        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/calendar';

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
        $this->logger->log(Logger::DEBUG, 'Updated calendar for ' . $cdbid->toNative() . '. commandId is ' . $commandId);

        return $commandId;
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
        $this->logger->log(Logger::DEBUG, 'Updated location for ' . $cdbid->toNative() . '. commandId is ' . $commandId);

        return $commandId;
    }

    /**
     * @inheritdoc
     */
    public function addMediaObject($file, StringLiteral $description, StringLiteral $copyright)
    {
        $savedFile = $this->downloadFile($file);

        $ch = curl_init();

        $postBody = array();
        $curlFile = curl_file_create($savedFile);
        $postBody['file'] = $curlFile;
        $postBody['description'] = $description;
        $postBody['copyrightHolder'] = $copyright;
        $postBody['language'] = 'nl';

        curl_setopt($ch, CURLOPT_URL, $this->url .'images/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = 'Content-Type: multipart/form-data';
        $headers[] = 'Accept: application/json';
        $headers[] = 'Authorization: ' . 'Bearer ' . $this->token;
        $headers[] = 'X-Api-Key: ' . $this->apiKey;
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postBody);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $bodyResponse = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $imageId =  $resp['imageId'];
        $this->logger->log(Logger::DEBUG, 'Added media from ' . $file . '. Got  ' . $imageId);

        return new UUID($imageId);
    }

    /**
     * @inheritdoc
     */
    public function addImage(UUID $cdbid, UUID $mediaObjectId)
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/images/';

        $request = $client->post(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $request->setBody('{ "mediaObjectId": "' . $mediaObjectId . '" }');
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $commandId =  $resp['commandId'];
        $this->logger->log(Logger::DEBUG, 'Added location for ' . $cdbid->toNative() . '. commandId is ' . $commandId);

        return $commandId;
    }

    /**
     * @inheritdoc
     */
    public function updateImage(UUID $cdbid, UUID $mediaObjectId, StringLiteral $description, StringLiteral $copyrightHolder)
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/images/' . $mediaObjectId->toNative();

        $request = $client->put(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $request->setBody('{ "description": "' . $description . '", "copyrightHolder": "' . $copyrightHolder . '" }');
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $commandId =  $resp['commandId'];
        $this->logger->log(Logger::DEBUG, 'Updated image for ' . $cdbid->toNative() . '. commandId is ' . $commandId);

        return $commandId;
    }

    /**
     * @inheritdoc
     */
    public function deleteImage(UUID $cdbid, UUID $mediaObjectId)
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/images/' . $mediaObjectId->toNative();

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
        $this->logger->log(Logger::DEBUG, 'Deleted image for ' . $cdbid->toNative() . '. commandId is ' . $commandId);

        return $commandId;
    }

    /**
     * @inheritdoc
     */
    public function setMainImage(UUID $cdbid, UUID $mediaObjectId)
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/images/main';

        $request = $client->put(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $request->setBody('{ "mediaObjectId": "' . $mediaObjectId . '" }');
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $commandId =  $resp['commandId'];
        $this->logger->log(Logger::DEBUG, 'Selected mainImage for ' . $cdbid->toNative() . '. commandId is ' . $commandId);

        return $commandId;
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
        $this->logger->log(Logger::DEBUG, 'Updated targetAudience for ' . $cdbid->toNative() . '. commandId is ' . $commandId);

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
        $this->logger->log(Logger::DEBUG, 'Updated bookingInfo for ' . $cdbid->toNative() . '. commandId is ' . $commandId);

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
        $this->logger->log(Logger::DEBUG, 'Added label for ' . $cdbid->toNative() . '. commandId is ' . $commandId);

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
        $this->logger->log(Logger::DEBUG, 'Deleted label for ' . $cdbid->toNative() . '. commandId is ' . $commandId);

        return $commandId;
    }

    /**§1§
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
        $this->logger->log(Logger::DEBUG, 'Updated organizer for ' . $cdbid->toNative() . '. commandId is ' . $commandId);

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
        $this->logger->log(Logger::DEBUG, 'Published event ' . $cdbid->toNative() . '. commandId is ' . $commandId);

        return $commandId;
    }

    private function downloadFile($fileLocation)
    {
        $fileName = basename($fileLocation);
        $savedFile = $this->filesFolder . $fileName;
        file_put_contents($savedFile, file_get_contents($fileLocation));
        return $savedFile;
    }
}
