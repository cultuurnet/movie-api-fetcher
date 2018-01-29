<?php

namespace CultuurNet\MovieApiFetcher\Formatter;

use CultuurNet\TransformEntryStore\Stores\RepositoryInterface;
use Guzzle\Http\Client;
use ValueObjects\StringLiteral\StringLiteral;

class Formatter implements FormatterInterface
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @inheritdoc
     */
    public function format($name, $type, $theme, $location, $calendar)
    {
        return new StringLiteral(
            '{  "name": {    "nl": "' . $name .
               '"  },  "type": {    "id": "' . $type .
               '",    "label": "Film",    "domain": "eventtype"  },  "theme": {    "id": "' . $theme .
               '",    "label": "' . $this->getThemeName($theme) . '",    "domain": "theme"  },  "location": {    "id": "' . $location .
               '",    "name": "Kinepolis",    "address": {     ' . $this->getAddress($location) . '    }  }, "calendarType": "multiple", "timeSpans": [ ' . $calendar   .  ' ],  "startDate": "2019-05-07T12:02:53+00:00",  "endDate": "2019-05-09T14:02:53+00:00"}'
        );
    }

    /**
     * @inheritdoc
     */
    public function formatEvent($externalId)
    {
        $name = $this->repository->getName($externalId);
        $typeId = $this->repository->getTypeId($externalId);
        $themeId = $this->repository->getThemeId($externalId);
        $locationId = $this->repository->getLocationCdbid($externalId);
        $calendar = $this->repository->getCalendar($externalId);
        $calendarString = $this->getCalendarString($calendar);

        return new StringLiteral(
            '{  "name": {    "nl": "' . $name .
            '"  },  "type": {    "id": "' . $typeId .
            '",    "label": "Film",    "domain": "eventtype"  },  "theme": {    "id": "' . $themeId .
            '",    "label": "' . $themeId . '",    "domain": "theme"  },  "location": {    "id": "' . $locationId .
            '",    "name": "Kinepolis",    "address": {     ' . $this->getAddress($locationId) . '    }  }, ' . $calendarString . '}'
        );
    }

    /**
     * Formatter constructor.
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    private function getThemeName($cnetId)
    {
        switch ($cnetId) {
            case '1.7.1.0.0':
                return 'Documentaires en reportages';
                break;
            case '1.7.2.0.0':
                return 'Actie en avontuur';
                break;
            case '1.7.3.0.0':
                return 'Komedie';
                break;
            case '1.7.4.0.0':
                return 'Drama';
                break;
            case '1.7.6.0.0':
                return 'Griezelfilm of horror';
                break;
            case '1.7.7.0.0':
                return 'Science fiction';
                break;
            case '1.7.10.0.0':
                return 'Filmmusical';
                break;
            case '1.7.12.0.0':
                return 'Animatie en kinderfilms';
                break;
            case '1.7.14.0.0':
                return 'Meerdere filmgenres';
                break;
            case '1.7.15.0.0':
                return 'Thriller';
                break;
            default:
                return 'Meerdere filmgenres';
        }
    }

    private function getAddress($location)
    {
        $client = new Client();
        $request = $client->get(
            'https://io.uitdatabank.be/place/' . $location,
            [
                'content-type' => 'application/json',
            ],
            []
        );

        $response = $request->send();

        $body  = $response->getBody();

        $place = json_decode($body, true);

        if (isset($place['address']['nl'])) {
            $country = $place['address']['nl']['addressCountry'];
            $locality = $place['address']['nl']['addressLocality'];
            $postalCode = $place['address']['nl']['postalCode'];
            $streetAddress = $place['address']['nl']['streetAddress'];
        } else {
            $country = $place['address']['addressCountry'];
            $locality = $place['address']['addressLocality'];
            $postalCode = $place['address']['postalCode'];
            $streetAddress = $place['address']['streetAddress'];
        }

        return '"addressCountry": "' . $country .'",      "addressLocality": "' . $locality . '",      "postalCode": "' . $postalCode . '",      "streetAddress": "' . $streetAddress . '"';

    }

    private function getCalendarString($calendarArray)
    {
        sort($calendarArray);
        $total = count($calendarArray);

        $calendar = '"calendarType": "multiple", "timeSpans": [ ';
        foreach ($calendarArray as $timeStamp) {
            $calendar .= '{ "start": "' .
                $timeStamp['date'] . 'T' . $timeStamp['time_start'] . '+00:00' .
                '", "end": "' . $timeStamp['date'] . 'T' . $timeStamp['time_end'] . '+00:00' . '" },';
        }
        $calendar = substr($calendar, 0, -1);
        $calendar .= '], "startDate": "' . $calendarArray[0]['date'] . 'T' . $timeStamp['time_start'] . '+00:00' .
            '", "endDate": "' . $calendarArray[$total - 1]['date'] . 'T' . $timeStamp['time_end'] . '+00:00' . '"';

        return $calendar;
    }
}
