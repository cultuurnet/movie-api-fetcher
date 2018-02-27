<?php

namespace CultuurNet\MovieApiFetcher\Formatter;

use CultuurNet\TransformEntryStore\Stores\RepositoryInterface;
use DOMDocument;
use Guzzle\Http\Client;
use ValueObjects\StringLiteral\StringLiteral;

class Formatter implements FormatterInterface
{
    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var string
     */
    private $url;

    /**
     * @inheritdoc
     */
    public function formatEvent($externalId)
    {
        $name = $this->repository->getName($externalId);
        $typeId = $this->repository->getTypeId($externalId);
        $themeId = $this->repository->getThemeId($externalId);
        $locationId = $this->repository->getLocationCdbid($externalId);
        $address = $this->getAddress($locationId);
        $calendar = $this->repository->getCalendar($externalId);
        sort($calendar);
        $playCount = count($calendar);

        $arr = array();
        $arr['name']['nl'] = $name->toNative();

        $arr['type']['id'] = $typeId->toNative();
        $arr['type']['label'] = 'Film';
        $arr['type']['domain'] = 'eventtype';

        if (isset($themeId)) {
            $arr['theme']['id'] = $themeId->toNative();
            $arr['theme']['label'] = $this->getThemeName($themeId);
            $arr['theme']['domain'] = 'theme';
        }

        $arr['location']['id'] = $locationId->toNative();
        $arr['location']['name'] = $address['name'];
        $arr['location']['address']['addressCountry'] = $address['addressCountry'];
        $arr['location']['address']['addressLocality'] = $address['addressLocality'];
        $arr['location']['address']['postalCode'] = $address['postalCode'];
        $arr['location']['address']['streetAddress'] = $address['streetAddress'];

        $arr['calendarType'] = 'multiple';

        for ($i = 0; $i < $playCount; $i++) {
            $arr['timeSpans'][$i]['start'] = $this->formatStart($calendar[$i]);
            $arr['timeSpans'][$i]['end'] = $this->formatEnd($calendar[$i]);
        }

        $arr['startDate'] = $this->formatStart($calendar[0]);
        $arr['endDate'] = $this->formatEnd($calendar[$playCount - 1]);

        return new StringLiteral(json_encode($arr));
    }

    /**
     * @inheritdoc
     */
    public function formatCalendar($externalId)
    {
        $calendar = $this->repository->getCalendar($externalId);
        sort($calendar);
        $playCount = count($calendar);

        $arr = array();

        $arr['calendarType'] = 'multiple';

        for ($i = 0; $i < $playCount; $i++) {
            $arr['timeSpans'][$i]['start'] = $this->formatStart($calendar[$i]);
            $arr['timeSpans'][$i]['end'] = $this->formatEnd($calendar[$i]);
        }

        $arr['startDate'] = $this->formatStart($calendar[0]);
        $arr['endDate'] = $this->formatEnd($calendar[$playCount - 1]);

        return new StringLiteral(json_encode($arr));
    }

    /**
     * @inheritdoc
     */
    public function formatPrice($externalId)
    {
        $price = $this->repository->getPrice($externalId);
        $count = count($price);

        $arr = array();

        for ($i = 0; $i < $count; $i++) {
            $tarif = array();

            $tarif['category'] = $price[$i]['is_base_price'] == 1 ? 'base' : 'tariff';
            $tarif['name'] = $price[$i]['name'] == 'base' ? 'Basistarief' : $price[$i]['name'];
            $tarif['price'] = (float) $price[$i]['price'];
            $tarif['priceCurrency'] = $price[$i]['currency'];

            $arr[] = $tarif;
        }

        return new StringLiteral(json_encode($arr));
    }

    /**
     * @param $externalId
     * @return StringLiteral
     */
    public function formatProduction($externalId) {
        //
        $eventcats = array();
        $relevents = array(); //$this->repository->get

        //

        $dom = new DOMDocument('1.0', 'utf-8');
        $cdbxml = $dom->createElement('cdbxml');
        $dom->appendChild($cdbxml);

        $xmlns_xsi = $dom->createAttribute('xmlns:xsi');
        $cdbxml->appendChild($xmlns_xsi);
        $xmlns_xsi_value = $dom->createTextNode('http://www.w3.org/2001/XMLSchema-instance');
        $xmlns_xsi->appendChild($xmlns_xsi_value);

        $xmlns_xsd = $dom->createAttribute('xmlns:xsd');
        $cdbxml->appendChild($xmlns_xsd);
        $xmlns_xsd_value = $dom->createTextNode('http://www.w3.org/2001/XMLSchema');
        $xmlns_xsd->appendChild($xmlns_xsd_value);

        $xmlns = $dom->createAttribute('xmlns');
        $cdbxml->appendChild($xmlns);
        $xmlns_value = $dom->createTextNode('http://www.cultuurdatabank.com/XMLSchema/CdbXSD/3.3/FINAL');
        $xmlns->appendChild($xmlns_value);

        $production = $dom->createElement('production');

        $categories = $dom->createElement('categories');
        $categorytype = $dom->createElement('category');

        $catid = $dom->createAttribute('catid');
        $catid_value = $dom->createTextNode('0.50.6.0.0');
        $catid->appendChild($catid_value);

        $type = $dom->createAttribute('type');
        $type_value = $dom->createTextNode('eventtype');
        $type->appendChild($type_value);

        $categorytype_value = $dom->createTextNode('Film');

        $categorytype->appendChild($catid);
        $categorytype->appendChild($type);
        $categorytype->appendChild($categorytype_value);

        $categories->appendChild($categorytype);

        $production->appendChild($categories);

        $productionDetails = $dom->createElement('productiondetails');


        $productionDetail = $dom->createElement('productiondetail');
        $lang = $dom->createAttribute('lang');
        $lang_value = $dom->createTextNode('nl');
        $lang->appendChild($lang_value);
        $productionDetail->appendChild($lang);

        // Media

        $shortdescription = $dom->createElement('shortdescription');
        $shortdescription_value = $dom->createTextNode('Neo thinks he \'s Superman!');
        $shortdescription->appendChild($shortdescription_value);
        $productionDetail->appendChild($shortdescription);

        $title = $dom->createElement('title');
        $title_value = $dom->createTextNode('The Matrix');
        $title->appendChild($title_value);
        $productionDetail->appendChild($title);

        $productionDetails->appendChild($productionDetail);

        $production->appendChild($productionDetails);

        $relatedevents = $dom->createElement('relatedevents');
        foreach ($relevents as $relevent) {
            $id = $dom->createElement('id');
            $cdbid = $dom->createAttribute('cdbid');
            $cdbid_value = $dom->createTextNode($relevent);
            $cdbid->appendChild($cdbid_value);
            $id->appendChild($cdbid);
            $relatedevents->appendChild($id);
        }
        $production->appendChild($relatedevents);

        $cdbxml->appendChild($production);

        return new StringLiteral($dom->saveXml());


    }

    /**
     * Formatter constructor.
     * @param RepositoryInterface $repository
     * @param $url
     */
    public function __construct(RepositoryInterface $repository, $url)
    {
        $this->repository = $repository;
        $this->url = $url;
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
            $this->url . 'place/' . $location,
            [
                'content-type' => 'application/json',
            ],
            []
        );

        $response = $request->send();

        $body  = $response->getBody();

        $place = json_decode($body, true);

        $address = array();

        if (isset($place['name']['nl'])) {
            $address['name'] = $place['name']['nl'];
        } else {
            $address['name'] = $place['name'];
        }
        if (isset($place['address']['nl'])) {
            $address['addressCountry'] = $place['address']['nl']['addressCountry'];
            $address['addressLocality'] = $place['address']['nl']['addressLocality'];
            $address['postalCode'] = $place['address']['nl']['postalCode'];
            $address['streetAddress'] = $place['address']['nl']['streetAddress'];
        } else {
            $address['addressCountry'] = $place['address']['addressCountry'];
            $address['addressLocality'] = $place['address']['addressLocality'];
            $address['postalCode'] = $place['address']['postalCode'];
            $address['streetAddress'] = $place['address']['streetAddress'];
        }

        return $address;
    }

    private function formatStart($playTime)
    {
        return $playTime['date'] . 'T' . $playTime['time_start'] . '+00:00';
    }

    private function formatEnd($playTime)
    {
        $dateString = $playTime['date'];
        if ($playTime['time_end'] < $playTime['time_start']) {
            $d = \DateTime::createFromFormat('Y-m-d', $playTime['date']);
            $di = new \DateInterval('P1D');
            $d->add($di);
            $dateString = $d->format('Y-m-d');
        }
        return $dateString . 'T' . $playTime['time_end'] . '+00:00';
    }
}
