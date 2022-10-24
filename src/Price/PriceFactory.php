<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Price;

use Guzzle\Http\Client;
use ValueObjects\Web\Url;

class PriceFactory implements PriceFactoryInterface
{
    /**
     * @inheritdoc
     */
    public function getPriceMatrix(Url $theatreUrl, $token)
    {
        $client = new Client();
        $request = $client->get(
            (string) $theatreUrl,
            [
                'content-type' => 'application/json',
                'Authorization' => $token,
                'User-Agent' => 'Kinepolis-Publiq',
            ],
            []
        );

        $response = $request->send();

        $body  = $response->getBody();
        $theatres = json_decode($body, true);

        $priceMatrix = [];
        foreach ($theatres['theatres'] as $theatre) {
            $prices = $this->getPricesForTheatre($theatreUrl, $token, $theatre['tid']);
            $priceMatrix[$theatre['tid']] = $prices;
        }

        return $priceMatrix;
    }

    private function getPricesForTheatre(Url $theatreUrl, $token, $tid)
    {
        $client = new Client();
        $request = $client->get(
            (string) $theatreUrl . '/' . $tid,
            [
                'content-type' => 'application/json',
                'Authorization' => $token,
                'User-Agent' => 'Kinepolis-Publiq',
            ],
            []
        );

        $response = $request->send();

        $body  = $response->getBody();
        $theatre = json_decode($body, true);

        $tariffs = $theatre['theatres'][0]['tariffs'];
        $parsedTariffs = [];
        foreach ($tariffs[0]['tarifs'] as $tarif) {
            $price = (float) (str_replace(',', '.', str_replace('€ ', '', $tarif[0])));

            if ($tarif[1] == 'Normaal tarief') {
                $parsedTariffs['base'] = $price;
            } elseif ($tarif[1] == 'Kortingstarief') {
                $parsedTariffs['Kortingstarief'] = $price;
            } elseif ($tarif[1] == 'Kinepolis Student Card') {
                $parsedTariffs['Kinepolis Student Card'] = $price;
            } elseif ($tarif[1] == 'Supplement Film Lange Speelduur (>/=2u15)') {
                $parsedTariffs['long_movies'] = $price;
            }
        }
        return $parsedTariffs;
    }
}
