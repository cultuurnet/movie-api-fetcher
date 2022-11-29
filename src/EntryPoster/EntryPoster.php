<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\EntryPoster;

use CultuurNet\TransformEntryStore\ValueObjects\AgeRange\AgeRange;
use CultuurNet\TransformEntryStore\ValueObjects\BookingInfo\BookingInfo;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Monolog\Logger;
use ValueObjects\Identity\UUID;

class EntryPoster implements EntryPosterInterface
{
    private string $token;

    private string $apiKey;

    private string $url;

    private string $filesFolder;

    private Logger $logger;

    private Client $client;

    public function postMovie(string $jsonMovie): ?UUID
    {
        $uri = $this->url . 'events/';

        $request = new Request(
            'POST',
            $uri,
            $this->getHeaders(),
            $jsonMovie
        );

        try {
            $response = $this->client->send($request);
        } catch (\Exception $e) {
            $this->logger->log(Logger::ERROR, 'Failed to post movie, message:  ' . $e->getMessage());
            $this->logger->log(Logger::DEBUG, $jsonMovie);
            return null;
        }

        $bodyResponse = $response->getBody()->getContents();

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

        $uri = $this->url . 'productions/?name=' . $name . '&start=0&limit=15';

        $request = new Request(
            'GET',
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
            ]
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);

        $totalItems = $resp['totalItems'];

        if ($totalItems > 0) {
            $productionId = $resp['member'][0]['production_id'];

            foreach ($production['eventIds'] as $eventId) {

                if (!in_array($eventId === $resp['member'][0]['events'])) {
                    $uri = $this->url . 'productions/' . $productionId . '/events/' . $eventId;

                    $request = new Request(
                        'PUT',
                        $uri,
                        $this->getHeaders()
                    );
                    try {
                        $response = $this->client->send($request);
                        $bodyResponse = $response->getBody()->getContents();

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
            $postUri = $this->url . 'productions/';
            $postRequest = new Request(
                'POST',
                $postUri,
                $this->getHeaders(),
                $jsonProduction
            );

            try {
                $response = $this->client->send($postRequest);
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
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/name/nl';

        $requestBody = [];
        $requestBody['name'] = $name;

        $request = new Request(
            'PUT',
            $uri,
            $this->getHeaders(),
            json_encode($requestBody)
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated name for ' . $cdbid->toNative() . '.');
    }

    public function updateDescription(UUID $cdbid, string $description): void
    {
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/description/nl';

        $requestBody = [];
        $requestBody['description'] = $description;

        $request = new Request(
            'PUT',
            $uri,
            $this->getHeaders(),
            json_encode($requestBody)
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated description for ' . $cdbid->toNative() . '.');
    }

    public function __construct(string $token_provider, string $refresh, string $apiKey, string $url, string $filesFolder, Logger $logger)
    {
        $this->client = new Client();
        $token = $this->getToken($token_provider, $refresh, $apiKey);
        $this->token = $token;
        $this->apiKey = $apiKey;
        $this->url = $url;
        $this->filesFolder = $filesFolder;
        $this->logger = $logger;
    }

    public function updateEventType(UUID $cdbid, string $type): void
    {
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/type/' . $type;

        $request = new Request(
            'PUT',
            $uri,
            $this->getHeaders(),
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated eventType for ' . $cdbid->toNative() . '.');
    }

    public function updateEventTheme(UUID $cdbid, string $theme): void
    {
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/theme/' . $theme;

        $request = new Request(
            'PUT',
            $uri,
            $this->getHeaders()
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated eventTheme for ' . $cdbid->toNative() . '.');
    }

    public function updateCalendar(UUID $cdbid, string $calendar): void
    {
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/calendar';

        $request = new Request(
            'PUT',
            $uri,
            $this->getHeaders(),
            $calendar
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated calendar for ' . $cdbid->toNative() . '.');
        $this->logger->log(Logger::DEBUG, $calendar);
    }

    public function updateLocation(UUID $cdbid, UUID $locationId): void
    {
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/location/' . $locationId->toNative();

        $request = new Request(
            'PUT',
            $uri,
            $this->getHeaders()
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

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
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/images/';

        $requestBody = [];
        $requestBody['mediaObjectId'] = $mediaObjectId->toNative();

        $request = new Request(
            'POST',
            $uri,
            $this->getHeaders(),
            json_encode($requestBody)
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Added location for ' . $cdbid->toNative() . '. ');
    }

    public function updateImage(UUID $cdbid, UUID $mediaObjectId, string $description, string $copyrightHolder): void
    {
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/images/' . $mediaObjectId->toNative();

        $requestBody = [];
        $requestBody['description'] = $description;
        $requestBody['copyrightHolder'] = $copyrightHolder;

        $request = new Request(
            'PUT',
            $uri,
            $this->getHeaders(),
            json_encode($requestBody)
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated image for ' . $cdbid->toNative() . '. ');
    }

    public function deleteImage(UUID $cdbid, UUID $mediaObjectId): void
    {
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/images/' . $mediaObjectId->toNative();

        $request = new Request(
            'DELETE',
            $uri,
            $this->getHeaders()
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Deleted image for ' . $cdbid->toNative() . '.');
    }

    public function setMainImage(UUID $cdbid, UUID $mediaObjectId): void
    {
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/images/main';

        $requestBody = [];
        $requestBody['mediaObjectId'] = $mediaObjectId->toNative();

        $request = new Request(
            'PUT',
            $uri,
            $this->getHeaders(),
            json_encode($requestBody)
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Selected mainImage for ' . $cdbid->toNative() . '.');
    }

    public function updateTargetAudience(UUID $cdbid, $audience): void
    {
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/audience';

        $requestBody = [];
        $requestBody['audience'] = $audience;

        $request = new Request(
            'PUT',
            $uri,
            $this->getHeaders(),
            json_encode($requestBody)
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated targetAudience for ' . $cdbid->toNative() . '.');
    }

    public function updateBookingInfo(UUID $cdbid, BookingInfo $bookingInfo): void
    {
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/bookingInfo';

        $requestBody = [];
        $requestBody['bookingInfo']['url'] = $bookingInfo;
        $requestBody['bookingInfo']['urlLabel'] = $bookingInfo;
        $requestBody['bookingInfo']['email'] = $bookingInfo;
        $requestBody['bookingInfo']['phone'] = $bookingInfo;
        $requestBody['bookingInfo']['availabilityStarts'] = $bookingInfo;
        $requestBody['bookingInfo']['availabilityEnds'] = $bookingInfo;

        $request = new Request(
            'PUT',
            $uri,
            $this->getHeaders(),
            json_encode($requestBody)
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated bookingInfo for ' . $cdbid->toNative() . '.');
    }

    public function updateContactInfo(UUID $cdbid, string $contactPoint): void
    {
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/contactPoint';

        $request = new Request(
            'PUT',
            $uri,
            $this->getHeaders(),
            $contactPoint
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated contactPoint for ' . $cdbid->toNative() . '.');
        $this->logger->log(Logger::DEBUG, $contactPoint);
    }

    public function addLabel(UUID $cdbid, string $label): void
    {
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/labels/' . $label;

        $request = new Request(
            'PUT',
            $uri,
            $this->getHeaders()
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Added label for ' . $cdbid->toNative() . '.');
    }

    public function deleteLabel(UUID $cdbid, string $label): void
    {
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/labels/' . $label;

        $request = new Request(
            'DELETE',
            $uri,
            $this->getHeaders()
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Deleted label for ' . $cdbid->toNative() . '.');
    }

    public function updateOrganizer(UUID $cdbid, UUID $organizerId): void
    {
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/organizer/' . $organizerId->toNative();

        $request = new Request(
            'PUT',
            $uri,
            $this->getHeaders(),
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated organizer for ' . $cdbid->toNative() . '. ');
    }

    public function updatePriceInfo(UUID $cdbid, string $priceInfo): void
    {
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/priceInfo';

        $request = new Request(
            'PUT',
            $uri,
            $this->getHeaders(),
            $priceInfo
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated priceInfo for ' . $cdbid->toNative() . '.');
        $this->logger->log(Logger::DEBUG, $priceInfo);
    }

    public function updateAgeRange(UUID $cdbid, AgeRange $typicalAgeRange): void
    {
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/typicalAgeRange';
        $body = '{"typicalAgeRange": "' . $typicalAgeRange->getAgeFrom() . '-' . $typicalAgeRange->getAgeTo() . '"}';

        $request = new Request(
            'PUT',
            $uri,
            $this->getHeaders(),
            $body
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated typicalAgeRange for ' . $cdbid->toNative() . '.');
        $this->logger->log(Logger::DEBUG, (string) $typicalAgeRange);
    }

    public function deleteAgeRange(UUID $cdbid): void
    {
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/typicalAgeRange';

        $request = new Request(
            'DELETE',
            $uri,
            $this->getHeaders()
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Deleted typicalAgeRange for ' . $cdbid->toNative() . '. ');
    }

    public function updateFacilities(UUID $cdbid, string $facilities): void
    {
        $uri = $this->url . 'events/' . $cdbid->toNative() . '/facilities';

        $request = new Request(
            'PUT',
            $uri,
            $this->getHeaders(),
            $facilities
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $this->logger->log(Logger::DEBUG, 'Updated facilities for ' . $cdbid->toNative() . '.');
        $this->logger->log(Logger::DEBUG, $facilities);
    }


    public function linkProduction(string $title, UUID $eventId): void
    {
        $uri = $this->url . 'productions/?name=' . $title . '&start=0&limit=15';

        $request = new Request(
            'GET',
            $uri,
            $this->getHeaders(),
        );

        $response = $this->client->send($request);

        $bodyResponse = $response->getBody()->getContents();

        $resp = json_decode(utf8_encode($bodyResponse), true);
        $totalItems = $resp['totalItems'];

        if ($totalItems > 0) {
            $productionId = $resp['member'][0]['production_id'];
            echo $productionId;
            echo PHP_EOL;
            if (!in_array($eventId->toNative(), $resp['member'][0]['events'])) {
                $putUri = $this->url . 'productions/' . $productionId . '/events/' . $eventId->toNative();

                $putRequest = new Request(
                    'PUT',
                    $putUri,
                    $this->getHeaders()
                );
                $putResponse = $this->client->send($putRequest);
                $this->logger->log(Logger::DEBUG, 'Linked event ' . $eventId->toNative() . ' to production ' . $productionId . '.');
            } else {
                $this->logger->log(Logger::DEBUG, $eventId->toNative() . '  already linked to production.');
            }
        } else {
            $postUri = $this->url . 'productions/';

            $postRequest = new Request(
                'POST',
                $postUri,
                $this->getHeaders(),
                '{"name":"TestTTT","eventIds":["8d016a92-c6c8-42f5-9eaf-15fbb24c6a36","5144bcab-d7e0-4763-bd45-edc15fea97c6"]}'
            );
            $postResponse = $this->client->send($postRequest);
        }

        //$this->logger->log(Logger::DEBUG, 'Updated facilities for ' . $cdbid->toNative() . '.');
    }

    public function publishEvent(UUID $cdbid): void
    {
        $uri = $this->url . 'event/' . $cdbid->toNative();

        $request = new Request(
            'PATCH',
            $uri,
            [
                'Authorization' => 'Bearer ' . $this->token,
                'x-api-key' => $this->apiKey,
                'content-type' => 'application/ld+json;domain-model=Publish',
            ],
        );

        $response = $this->client->send($request);
        $bodyResponse = $response->getBody()->getContents();

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
        $uri = $token_provider . 'refresh?apiKey=' . $apiKey . '&refresh=' . $refresh;

        $request = new Request(
            'GET',
            $uri
        );

        $response = $this->client->send($request);
        return $response->getBody()->getContents();
    }

    private function getHeaders(): array
    {
        return [
            'Authorization' => 'Bearer ' . $this->token,
            'x-api-key' => $this->apiKey,
        ];
    }
}
