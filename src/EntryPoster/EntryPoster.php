<?php

namespace CultuurNet\MovieApiFetcher\EntryPoster;

use CultuurNet\TransformEntryStore\ValueObjects\AgeRange\AgeRange;
use CultuurNet\TransformEntryStore\ValueObjects\BookingInfo\BookingInfo;
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
            $this->logger->log(Logger::DEBUG, $jsonMovie->toNative());
            return null;
        }

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $cdbid =  $resp['eventId'];
        $this->logger->log(Logger::DEBUG, 'Posted movie ' . $cdbid);
        $this->logger->log(Logger::DEBUG, $jsonMovie->toNative());

        return new UUID($cdbid);
    }

    /**
     * @inheritdoc
     */
    public function postProduction(StringLiteral $jsonProduction)
    {
        $production = json_decode($jsonProduction->toNative(), true);
        $name = $production['name'];

        $client = new Client();
        $uri = (string) $this->url  . 'productions/?name=' . $name . '&start=0&limit=15';

        $request = $client->get(
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

        $totalItems = $resp["totalItems"];

        if ($totalItems > 0) {
            $productionId = $resp["member"][0]["production_id"];

            foreach ($production['eventIds'] as $eventId) {
                if (!in_array($eventId, $resp["member"][0]["events"])) {

                    $clientPutter = new Client();

                    $uri = $this->url . 'productions/' . $productionId . '/events/' . $eventId;

                    $request = $clientPutter->put(
                        $uri,
                        [
                            'Authorization' => 'Bearer ' . $this->token,
                            'x-api-key' => $this->apiKey,
                        ],
                        []
                    );
                    try {
                        $response = $request->send();
                        $bodyResponse = $response->getBody();

                        $resp = utf8_encode($bodyResponse);
                        $this->logger->log(Logger::DEBUG, 'Linked to production ' . $eventId . ' ' . $resp);
                    } catch (\Exception $e) {
                        $this->logger->log(Logger::ERROR, 'Failed to link production, message:  ' . $e->getMessage());
                        $this->logger->log(Logger::DEBUG, $eventId);
                    }
                } else {
                    $this->logger->log(Logger::DEBUG, 'Event already linked ' . $eventId);
                }
            }
        } elseif (sizeof($production['eventIds']) > 1) {
            $postClient = new Client();
            $postUri = (string) $this->url . 'productions/';
            $postRequest = $postClient->post(
                $postUri,
                [
                    'Authorization' => 'Bearer ' . $this->token,
                    'x-api-key' => $this->apiKey,
                ],
                []
            );

            $postRequest->setBody($jsonProduction->toNative());

            try {
                $response = $postRequest->send();
                $this->logger->log(Logger::DEBUG, 'Can\'t make production for ' . $name);
            } catch (\Exception $e) {
                echo 'error' . PHP_EOL;
                echo $e->getMessage();
            }
        } else {
            $this->logger->log(Logger::DEBUG, 'Can\'t make production for ' . $name);
        }

        // return new UUID($cdbid);
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
        $requestBody = array();
        $requestBody['name'] = $name->toNative();

        $request->setBody(json_encode($requestBody));
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated name for ' . $cdbid->toNative() . '.');
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
        $requestBody = array();
        $requestBody['description'] = $description->toNative();

        $request->setBody(json_encode($requestBody));
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated description for ' . $cdbid->toNative() . '.');
    }

    /**
     * EntryPoster constructor.
     * @param $token_provider
     * @param $refresh
     * @param $apiKey
     * @param $url
     * @param $filesFolder
     * @param Logger $logger
     */
    public function __construct($token_provider, $refresh, $apiKey, $url, $filesFolder, $logger)
    {
        $token = $this->getToken($token_provider, $refresh, $apiKey);
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
        $this->logger->log(Logger::DEBUG, 'Updated eventType for ' . $cdbid->toNative() . '.');
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
        $this->logger->log(Logger::DEBUG, 'Updated eventTheme for ' . $cdbid->toNative() . '.');
    }

    /**
     * @inheritdoc
     */
    public function updateCalendar(UUID $cdbid, StringLiteral $calendar)
    {
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

        $request->setBody($calendar->toNative());
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated calendar for ' . $cdbid->toNative() . '.');
        $this->logger->log(Logger::DEBUG, $calendar->toNative());
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
        $this->logger->log(Logger::DEBUG, 'Updated location for ' . $cdbid->toNative() . '.');
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
        $requestBody = array();
        $requestBody['mediaObjectId'] = $mediaObjectId->toNative();

        $request->setBody(json_encode($requestBody));
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Added location for ' . $cdbid->toNative() . '. ');
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
        $requestBody = array();
        $requestBody['description'] = $description->toNative();
        $requestBody['copyrightHolder'] = $copyrightHolder->toNative();

        $request->setBody(json_encode($requestBody));
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated image for ' . $cdbid->toNative() . '. ');
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
        $this->logger->log(Logger::DEBUG, 'Deleted image for ' . $cdbid->toNative() . '.');
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
        $requestBody = array();
        $requestBody['mediaObjectId'] = $mediaObjectId->toNative();

        $request->setBody(json_encode($requestBody));
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Selected mainImage for ' . $cdbid->toNative() . '.');
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
        $requestBody = array();
        $requestBody['audience'] = $audience;

        $request->setBody(json_encode($requestBody));
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated targetAudience for ' . $cdbid->toNative() . '.');
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
        $requestBody = array();
        $requestBody['bookingInfo']['url'] = $bookingInfo;
        $requestBody['bookingInfo']['urlLabel'] = $bookingInfo;
        $requestBody['bookingInfo']['email'] = $bookingInfo;
        $requestBody['bookingInfo']['phone'] = $bookingInfo;
        $requestBody['bookingInfo']['availabilityStarts'] = $bookingInfo;
        $requestBody['bookingInfo']['availabilityEnds'] = $bookingInfo;

        $request->setBody(json_encode($requestBody));
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated bookingInfo for ' . $cdbid->toNative() . '.');
    }

    /**
     * @inheritdoc
     */
    public function updateContactInfo(UUID $cdbid, StringLiteral $contactPoint)
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/contactPoint';

        $request = $client->put(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $request->setBody($contactPoint->toNative());
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated contactPoint for ' . $cdbid->toNative() . '.');
        $this->logger->log(Logger::DEBUG, $contactPoint->toNative());
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
        $this->logger->log(Logger::DEBUG, 'Added label for ' . $cdbid->toNative() . '.');
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
        $this->logger->log(Logger::DEBUG, 'Deleted label for ' . $cdbid->toNative() . '.');
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
        $this->logger->log(Logger::DEBUG, 'Updated organizer for ' . $cdbid->toNative() . '. ');
    }

    /**
     * @inheritdoc
     */
    public function updatePriceInfo(UUID $cdbid, StringLiteral $priceInfo)
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/priceInfo';

        $request = $client->put(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $request->setBody($priceInfo->toNative());
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated priceInfo for ' . $cdbid->toNative() . '.');
        $this->logger->log(Logger::DEBUG, $priceInfo->toNative());
    }

    /**
     * @inheritdoc
     */
    public function updateAgeRange(UUID $cdbid, AgeRange $typicalAgeRange)
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/typicalAgeRange';
        $body = '{"typicalAgeRange": "' . $typicalAgeRange->getAgeFrom()->toNative() . '-' . $typicalAgeRange->getAgeTo()->toNative() . '"}';

        $request = $client->put(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $request->setBody($body);
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated typicalAgeRange for ' . $cdbid->toNative() . '.');
        $this->logger->log(Logger::DEBUG, (string) $typicalAgeRange);
    }

    /**
     * @inheritdoc
     */
    public function deleteAgeRange(UUID $cdbid)
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/typicalAgeRange';

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
        $this->logger->log(Logger::DEBUG, 'Deleted typicalAgeRange for ' . $cdbid->toNative() . '. ');
    }

    /**
     * @inheritdoc
     */
    public function updateFacilities(UUID $cdbid, StringLiteral $facilities)
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/facilities';

        $request = $client->put(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ],
            []
        );

        $request->setBody($facilities->toNative());
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated facilities for ' . $cdbid->toNative() . '.');
        $this->logger->log(Logger::DEBUG, $facilities->toNative());
    }

    /**
     * @inheritdoc
     */
    public function linkProduction(StringLiteral $title, UUID $eventId)
    {
        $client = new Client();
        $uri = (string) $this->url . 'productions/?name=' . $title->toNative() . '&start=0&limit=15';

        $request = $client->get(
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
        $totalItems = $resp["totalItems"];

        if ($totalItems > 0) {
            $productionId = $resp["member"][0]["production_id"];
            echo $productionId;
            echo PHP_EOL;
            if (!in_array($eventId->toNative(), $resp["member"][0]["events"])) {

                $clientPutter = new Client();

                $putUri = (string) $this->url . 'productions/' . $productionId . '/events/' . $eventId->toNative();

                $putRequest = $clientPutter->put(
                    $putUri,
                    [
                        'Authorization' => 'Bearer ' . $this->token,
                        'x-api-key' => $this->apiKey,
                    ],
                    []
                );
                $putResponse = $putRequest->send();
                $this->logger->log(Logger::DEBUG, 'Linked event ' . $eventId->toNative() . ' to production ' . $productionId . '.');
            } else {
                $this->logger->log(Logger::DEBUG, $eventId->toNative() . '  already linked to production.');
            }
        } else {
            $postClient = new Client();
            $postUri = (string) $this->url . 'productions/';

            $postRequest = $postClient->post(
                $postUri,
                [
                    'Authorization' => 'Bearer ' . $this->token,
                    'x-api-key' => $this->apiKey,
                ],
                []
            );
            $postRequest->setBody('{"name":"TestTTT","eventIds":["8d016a92-c6c8-42f5-9eaf-15fbb24c6a36","5144bcab-d7e0-4763-bd45-edc15fea97c6"]}');
            $postResponse = $postRequest->send();
        }

        //$this->logger->log(Logger::DEBUG, 'Updated facilities for ' . $cdbid->toNative() . '.');


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
        $this->logger->log(Logger::DEBUG, 'Published event ' . $cdbid->toNative() . '.');
    }

    private function downloadFile($fileLocation)
    {
        $fileName = basename($fileLocation);
        $savedFile = $this->filesFolder . $fileName;
        file_put_contents($savedFile, file_get_contents($fileLocation));
        return $savedFile;
    }

    private function getToken($token_provider, $refresh, $apiKey)
    {
        $client = new Client();
        $uri = $token_provider . 'refresh?apiKey=' . $apiKey . '&refresh=' . $refresh;

        $request = $client->get(
            $uri
        );

        $response = $request->send();
        $bodyResponse = $response->getBody();
        return (string) $bodyResponse;
    }
}
