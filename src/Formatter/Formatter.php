<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Formatter;

use CultuurNet\TransformEntryStore\Stores\RepositoryInterface;
use DOMDocument;
use Guzzle\Http\Client;
use ValueObjects\Identity\UUID;

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
    public function formatEvent($externalId): string
    {
        $name = $this->repository->getName($externalId);
        $typeId = $this->repository->getTypeId($externalId);
        $themeId = $this->repository->getThemeId($externalId);
        $locationId = $this->repository->getLocationCdbid($externalId);
        $address = $this->getAddress($locationId);
        $calendar = $this->repository->getCalendar($externalId);
        sort($calendar);
        $playCount = count($calendar);

        $arr = [];
        $arr['mainLanguage'] = 'nl';
        $arr['name'] = $name;

        $arr['type']['id'] = $typeId;
        $arr['type']['label'] = 'Film';
        $arr['type']['domain'] = 'eventtype';

        if (isset($themeId)) {
            $arr['theme']['id'] = $themeId;
            $arr['theme']['label'] = $this->getThemeName($themeId);
            $arr['theme']['domain'] = 'theme';
        }

        $arr['location']['id'] = $locationId;
        $arr['location']['name'] = $address['name'];
        $arr['location']['address']['addressCountry'] = $address['addressCountry'];
        $arr['location']['address']['addressLocality'] = $address['addressLocality'];
        $arr['location']['address']['postalCode'] = $address['postalCode'];
        $arr['location']['address']['streetAddress'] = $address['streetAddress'];

        $arr['calendar']['calendarType'] = 'multiple';

        for ($i = 0; $i < $playCount; $i++) {
            $arr['calendar']['timeSpans'][$i]['start'] = $this->formatStart($calendar[$i]);
            $arr['calendar']['timeSpans'][$i]['end'] = $this->formatEnd($calendar[$i]);
        }

        //$arr['startDate'] = $this->formatStart($calendar[0]);
        //$arr['endDate'] = $this->formatEnd($calendar[$playCount - 1]);

        return json_encode($arr);
    }

    /**
     * @inheritdoc
     */
    public function formatCalendar($externalId): string
    {
        $calendar = $this->repository->getCalendar($externalId);
        sort($calendar);
        $playCount = count($calendar);

        $arr = [];

        $arr['calendarType'] = 'multiple';

        for ($i = 0; $i < $playCount; $i++) {
            $arr['timeSpans'][$i]['start'] = $this->formatStart($calendar[$i]);
            $arr['timeSpans'][$i]['end'] = $this->formatEnd($calendar[$i]);
        }

        $arr['startDate'] = $this->formatStart($calendar[0]);
        $arr['endDate'] = $this->formatEnd($calendar[$playCount - 1]);

        return json_encode($arr);
    }

    public function formatPrice($externalId): string
    {
        $price = $this->repository->getPrice($externalId);
        $count = count($price);

        $arr = [];

        for ($i = 0; $i < $count; $i++) {
            $tarif = [];

            $tarif['category'] = $price[$i]['is_base_price'] == 1 ? 'base' : 'tariff';
            $tarif['name']['nl'] = $price[$i]['name'] == 'base' ? 'Basistarief' : $price[$i]['name'];
            $tarif['price'] = (float) $price[$i]['price'];
            $tarif['priceCurrency'] = $price[$i]['currency'];

            $arr[] = $tarif;
        }

        return json_encode($arr);
    }

    public function formatProduction(string $externalIdProduction): string
    {
        $relevents = $this->repository->getCdbids($externalIdProduction);
        $imageId = null;
        if (isset($relevents) && count($relevents) > 0) {
            $firstEvent = new UUID($relevents[0]['cdbid_event']);
            $firstEventExternalId = $this->repository->getExternalId($firstEvent);
            $imageId = $this->repository->getImageId($firstEventExternalId);
        }
        $themeId = $this->repository->getThemeId($externalIdProduction);
        $description = $this->repository->getDescription($externalIdProduction);

        $dom = new DOMDocument('1.0', 'utf-8');
        $cdbxml = $dom->createElement('cdbxml');
        $dom->appendChild($cdbxml);

        $xmlnsXsi = $dom->createAttribute('xmlns:xsi');
        $cdbxml->appendChild($xmlnsXsi);
        $xmlnsXsiValue = $dom->createTextNode('http://www.w3.org/2001/XMLSchema-instance');
        $xmlnsXsi->appendChild($xmlnsXsiValue);

        $xmlnsXsd = $dom->createAttribute('xmlns:xsd');
        $cdbxml->appendChild($xmlnsXsd);
        $xmlnsXsdValue = $dom->createTextNode('http://www.w3.org/2001/XMLSchema');
        $xmlnsXsd->appendChild($xmlnsXsdValue);

        $xmlns = $dom->createAttribute('xmlns');
        $cdbxml->appendChild($xmlns);
        $xmlnsValue = $dom->createTextNode('http://www.cultuurdatabank.com/XMLSchema/CdbXSD/3.3/FINAL');
        $xmlns->appendChild($xmlnsValue);

        $production = $dom->createElement('production');

        $availablefrom = $dom->createAttribute('availablefrom');
        $availablefromValue = $dom->createTextNode('2018-01-01T00:00:00');
        $availablefrom->appendChild($availablefromValue);
        $production->appendChild($availablefrom);

        $availableto = $dom->createAttribute('availableto');
        $availabletoValue = $dom->createTextNode('2099-12-31T00:00:00');
        $availableto->appendChild($availabletoValue);
        $production->appendChild($availableto);

        $creationdate = $dom->createAttribute('creationdate');
        $creationdateValue = $dom->createTextNode('2018-01-01T00:00:00');
        $creationdate->appendChild($creationdateValue);
        $production->appendChild($creationdate);

        $cdbidProduction = $dom->createAttribute('cdbid');
        $cdbidProductionValue = $dom->createTextNode($this->repository->getProductionCdbid($externalIdProduction)->toNative());
        $cdbidProduction->appendChild($cdbidProductionValue);
        $production->appendChild($cdbidProduction);

        $externalid = $dom->createAttribute('externalid');
        $externalidValue = $dom->createTextNode($externalIdProduction);
        $externalid->appendChild($externalidValue);
        $production->appendChild($externalid);

        $createdBy = $dom->createAttribute('createdby');
        $createdByValue = $dom->createTextNode('imports@cultuurnet.be');
        $createdBy->appendChild($createdByValue);
        $production->appendChild($createdBy);

        $lastUpdatedBy = $dom->createAttribute('lastupdatedby');
        $lastUpdatedByValue = $dom->createTextNode('imports@cultuurnet.be');
        $lastUpdatedBy->appendChild($lastUpdatedByValue);
        $production->appendChild($lastUpdatedBy);

        $categories = $dom->createElement('categories');
        $categorytype = $dom->createElement('category');

        $catid = $dom->createAttribute('catid');
        $catidValue = $dom->createTextNode('0.50.6.0.0');
        $catid->appendChild($catidValue);

        $type = $dom->createAttribute('type');
        $typeValue = $dom->createTextNode('eventtype');
        $type->appendChild($typeValue);

        $categorytypeValue = $dom->createTextNode('Film');

        $categorytype->appendChild($catid);
        $categorytype->appendChild($type);
        $categorytype->appendChild($categorytypeValue);

        if (isset($themeId)) {
            $categoryTheme = $dom->createElement('category');

            $catidTheme = $dom->createAttribute('catid');
            $catidThemeValue = $dom->createTextNode($themeId);
            $catidTheme->appendChild($catidThemeValue);

            $themeType = $dom->createAttribute('type');
            $themeTypeValue = $dom->createTextNode('theme');
            $themeType->appendChild($themeTypeValue);

            $categorythemeValue = $dom->createTextNode($this->getThemeName($themeId));

            $categoryTheme->appendChild($catidTheme);
            $categoryTheme->appendChild($themeType);
            $categoryTheme->appendChild($categorythemeValue);
        }

        $categories->appendChild($categorytype);
        if (isset($themeId, $categoryTheme)) {
            $categories->appendChild($categoryTheme);
        }

        $production->appendChild($categories);

        $productionDetails = $dom->createElement('productiondetails');

        $productionDetail = $dom->createElement('productiondetail');
        $lang = $dom->createAttribute('lang');
        $langValue = $dom->createTextNode('nl');
        $lang->appendChild($langValue);
        $productionDetail->appendChild($lang);

        if (isset($imageId)) {
            $media = $dom->createElement('media');

            $file = $dom->createElement('file');
            $mainFile = $dom->createAttribute('main');
            $mainFileValue = $dom->createTextNode('true');
            $mainFile->appendChild($mainFileValue);
            $file->appendChild($mainFile);

            $copyright = $dom->createElement('copyright');
            $copyrightValue = $dom->createTextNode('Kinepolis');
            $copyright->appendChild($copyrightValue);
            $file->appendChild($copyright);

            $filename = $dom->createElement('filename');
            $filenameValue = $dom->createTextNode($this->formatImageName($imageId));
            $filename->appendChild($filenameValue);
            $file->appendChild($filename);

            $filetype = $dom->createElement('filetype');
            $filetypeValue = $dom->createTextNode('jpeg');
            $filetype->appendChild($filetypeValue);
            $file->appendChild($filetype);

            $hlink = $dom->createElement('hlink');
            $hlinkValue = $dom->createTextNode($this->formatImageUrl($imageId));
            $hlink->appendChild($hlinkValue);
            $file->appendChild($hlink);

            $mediaType = $dom->createElement('mediatype');
            $mediaTypeValue = $dom->createTextNode('photo');
            $mediaType->appendChild($mediaTypeValue);
            $file->appendChild($mediaType);

            $media->appendChild($file);

            $productionDetail->appendChild($media);
        }

        if (isset($description) && strlen($description)) {
            $shortdescription = $dom->createElement('shortdescription');
            $shortdescriptionValue = $dom->createTextNode($description);
            $shortdescription->appendChild($shortdescriptionValue);
            $productionDetail->appendChild($shortdescription);
        }

        $title = $dom->createElement('title');
        $titleValue = $dom->createTextNode($this->repository->getName($externalIdProduction));
        $title->appendChild($titleValue);
        $productionDetail->appendChild($title);

        $productionDetails->appendChild($productionDetail);

        $production->appendChild($productionDetails);

        if (isset($relevents)) {
            $relatedevents = $dom->createElement('relatedevents');
            foreach ($relevents as $relevent) {
                $id = $dom->createElement('id');
                $cdbid = $dom->createAttribute('cdbid');
                $cdbidValue = $dom->createTextNode($relevent['cdbid_event']);
                $cdbid->appendChild($cdbidValue);
                $id->appendChild($cdbid);
                $relatedevents->appendChild($id);
            }
            $production->appendChild($relatedevents);
        }

        $cdbxml->appendChild($production);

        return trim($dom->saveXml());
    }

    public function formatProductionJson(string $externalIdProduction): string
    {
        $relevents = $this->repository->getCdbids($externalIdProduction);
        $titleValue = $this->repository->getName($externalIdProduction);

        $productionInfo = [];
        $productionInfo['name'] = $titleValue;
        $eventIds = [];

        if (isset($relevents)) {
            foreach ($relevents as $relevent) {
                $eventIds[] = $relevent['cdbid_event'];
            }
        }
        $productionInfo['eventIds'] = $eventIds;
        return json_encode($productionInfo);
    }

    /**
     * Formatter constructor.
     * @param $url
     */
    public function __construct(RepositoryInterface $repository, $url)
    {
        $this->repository = $repository;
        $this->url = $url;
    }

    private function getThemeName($cnetId): string
    {
        switch ($cnetId) {
            case '1.7.1.0.0':
                return 'Documentaires en reportages';
            case '1.7.2.0.0':
                return 'Actie en avontuur';
            case '1.7.3.0.0':
                return 'Komedie';
            case '1.7.4.0.0':
                return 'Drama';
            case '1.7.6.0.0':
                return 'Griezelfilm of horror';
            case '1.7.7.0.0':
                return 'Science fiction';
            case '1.7.10.0.0':
                return 'Filmmusical';
            case '1.7.12.0.0':
                return 'Animatie en kinderfilms';
            case '1.7.14.0.0':
                return 'Meerdere filmgenres';
            case '1.7.15.0.0':
                return 'Thriller';
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
                'User-Agent' => 'Kinepolis-Publiq',
            ],
            []
        );

        $response = $request->send();

        $body  = $response->getBody();

        $place = json_decode($body, true);

        $address = [];

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

    private function formatEnd($playTime): string
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

    private function formatImageName(string $imageId)
    {
        return $imageId . '.jpeg';
    }

    private function formatImageUrl(string $imageId)
    {
        return $this->url . 'images/' . $this->formatImageName($imageId);
    }
}
