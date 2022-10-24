<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\EntryPoster;

use CultuurNet\TransformEntryStore\ValueObjects\AgeRange\AgeRange;
use CultuurNet\TransformEntryStore\ValueObjects\BookingInfo\BookingInfo;
use Guzzle\Http\Client;
use Monolog\Logger;
use ValueObjects\Identity\UUID;

class EntryPoster implements EntryPosterInterface
{
    private string $token;

    private string $apiKey;

    private string $url;

    private string $filesFolder;

    private Logger $logger;

    public function postMovie(string $jsonMovie): ?UUID
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

        $request->setBody($jsonMovie);

        try {
            $response = $request->send();
        } catch (\Exception $e) {
            $this->logger->log(Logger::ERROR, 'Failed to post movie, message:  ' . $e->getMessage());
            $this->logger->log(Logger::DEBUG, $jsonMovie);
            return null;
        }

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $cdbid =  $resp['eventId'];
        $this->logger->log(Logger::DEBUG, 'Posted movie ' . $cdbid);
        $this->logger->log(Logger::DEBUG, $jsonMovie);

        return new UUID($cdbid);
    }

    public function postProduction(string $jsonProduction): void
    {
        $production = json_decode($jsonProduction, true);
        $name = $production['name'];

        $client = new Client();
        $uri = (string) $this->url . 'productions/?name=' . $name . '&start=0&limit=15';

        $request = $client->get(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
                'User-Agent' => 'Kinepolis-Publiq',
            ],
            []
        );

        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);

        $totalItems = $resp['totalItems'];

        if ($totalItems > 0) {
            $productionId = $resp['member'][0]['production_id'];

            foreach ($production['eventIds'] as $eventId) {
                if (!in_array($eventId, $resp['member'][0]['events'])) {
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

            $postRequest->setBody($jsonProduction);

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

        //return new UUID($cdbid);
    }

    public function updateName(UUID $cdbid, string $name): void
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
        $requestBody = [];
        $requestBody['name'] = $name;

        $request->setBody(json_encode($requestBody));
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated name for ' . $cdbid->toNative() . '.');
    }

    public function updateDescription(UUID $cdbid, string $description): void
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
        $requestBody = [];
        $requestBody['description'] = $description;

        $request->setBody(json_encode($requestBody));
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated description for ' . $cdbid->toNative() . '.');
    }

    public function __construct(string $token_provider, string $refresh, string $apiKey, string $url, string $filesFolder, Logger $logger)
    {
        $token = $this->getToken($token_provider, $refresh, $apiKey);
        $this->token = $token;
        $this->apiKey = $apiKey;
        $this->url = $url;
        $this->filesFolder = $filesFolder;
        $this->logger = $logger;
    }

    public function updateEventType(UUID $cdbid, string $type): void
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/type/' . $type;

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

    public function updateEventTheme(UUID $cdbid, string $theme): void
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/theme/' . $theme;

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

    public function updateCalendar(UUID $cdbid, string $calendar): void
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

        $request->setBody($calendar);
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated calendar for ' . $cdbid->toNative() . '.');
        $this->logger->log(Logger::DEBUG, $calendar);
    }

    public function updateLocation(UUID $cdbid, UUID $locationId): void
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

    public function addMediaObject($file, string $description, string $copyright): UUID
    {
        $savedFile = $this->downloadFile($file);

        $ch = curl_init();

        $postBody = [];
        $curlFile = curl_file_create($savedFile);
        $postBody['file'] = $curlFile;
        $postBody['description'] = $description;
        $postBody['copyrightHolder'] = $copyright;
        $postBody['language'] = 'nl';

        curl_setopt($ch, CURLOPT_URL, $this->url . 'images/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = [];
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

    public function addImage(UUID $cdbid, UUID $mediaObjectId): void
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
        $requestBody = [];
        $requestBody['mediaObjectId'] = $mediaObjectId->toNative();

        $request->setBody(json_encode($requestBody));
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Added location for ' . $cdbid->toNative() . '. ');
    }

    public function updateImage(UUID $cdbid, UUID $mediaObjectId, string $description, string $copyrightHolder): void
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
        $requestBody = [];
        $requestBody['description'] = $description;
        $requestBody['copyrightHolder'] = $copyrightHolder;

        $request->setBody(json_encode($requestBody));
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated image for ' . $cdbid->toNative() . '. ');
    }

    public function deleteImage(UUID $cdbid, UUID $mediaObjectId): void
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

    public function setMainImage(UUID $cdbid, UUID $mediaObjectId): void
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
        $requestBody = [];
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
    public function updateTargetAudience(UUID $cdbid, $audience): void
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
        $requestBody = [];
        $requestBody['audience'] = $audience;

        $request->setBody(json_encode($requestBody));
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated targetAudience for ' . $cdbid->toNative() . '.');
    }

    public function updateBookingInfo(UUID $cdbid, BookingInfo $bookingInfo): void
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
        $requestBody = [];
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

    public function updateContactInfo(UUID $cdbid, string $contactPoint): void
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

        $request->setBody($contactPoint);
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated contactPoint for ' . $cdbid->toNative() . '.');
        $this->logger->log(Logger::DEBUG, $contactPoint);
    }

    public function addLabel(UUID $cdbid, string $label): void
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/labels/' . $label;

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

    public function deleteLabel(UUID $cdbid, string $label): void
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/labels/' . $label;

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

    public function updateOrganizer(UUID $cdbid, UUID $organizerId): void
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

    public function updatePriceInfo(UUID $cdbid, string $priceInfo): void
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

        $request->setBody($priceInfo);
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated priceInfo for ' . $cdbid->toNative() . '.');
        $this->logger->log(Logger::DEBUG, $priceInfo);
    }

    public function updateAgeRange(UUID $cdbid, AgeRange $typicalAgeRange): void
    {
        $client = new Client();
        $uri = (string) $this->url . 'events/' . $cdbid->toNative() . '/typicalAgeRange';
        $body = '{"typicalAgeRange": "' . $typicalAgeRange->getAgeFrom() . '-' . $typicalAgeRange->getAgeTo() . '"}';

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

    public function deleteAgeRange(UUID $cdbid): void
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

    public function updateFacilities(UUID $cdbid, string $facilities): void
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

        $request->setBody($facilities);
        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated facilities for ' . $cdbid->toNative() . '.');
        $this->logger->log(Logger::DEBUG, $facilities);
    }


    public function linkProduction(string $title, UUID $eventId): void
    {
        $client = new Client();
        $uri = (string) $this->url . 'productions/?name=' . $title . '&start=0&limit=15';

        $request = $client->get(
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
                'User-Agent' => 'Kinepolis-Publiq',
            ],
            []
        );

        $response = $request->send();

        $bodyResponse = $response->getBody();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $totalItems = $resp['totalItems'];

        if ($totalItems > 0) {
            $productionId = $resp['member'][0]['production_id'];
            echo $productionId;
            echo PHP_EOL;
            if (!in_array($eventId->toNative(), $resp['member'][0]['events'])) {
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

    public function publishEvent(UUID $cdbid): void
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

    private function downloadFile($fileLocation): string
    {
        $fileName = basename($fileLocation);
        $savedFile = $this->filesFolder . $fileName;
        file_put_contents($savedFile, file_get_contents($fileLocation));
        return $savedFile;
    }

    private function getToken($token_provider, $refresh, $apiKey): string
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
