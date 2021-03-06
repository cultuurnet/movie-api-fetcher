<?php

namespace CultuurNet\MovieApiFetcher\Identification;

use CultuurNet\MovieApiFetcher\Formatter\Formatter;
use CultuurNet\TransformEntryStore\Stores\RepositoryInterface;
use ValueObjects\StringLiteral\StringLiteral;

class FormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Formatter
     */
    private $formatter;

    /**
     * @var RepositoryInterface
     */
    private $repository;

    /**
     * @var string
     */
    private $url;

    protected function setUp()
    {
        $this->repository = $this->createMock(RepositoryInterface::class);
        $this->url = 'https://io-dev.uitdatabank.dev/';

        $this->formatter = new Formatter($this->repository, $this->url);
    }

    /**
     * @test
     */
    public function testFormatEvent()
    {

    }

    /**
     * @test
     */
    public function testFormatCalendar()
    {

    }

    /**
     * @test
     */
    public function testFormatPrice()
    {
        $externalIdMovie = new StringLiteral('Testm4321');

        $this->repository->expects($this->once())
            ->method('getPrice')
            ->with($externalIdMovie)
            ->willReturn(
                array(
                        0 =>
                        array(
                            'is_base_price' => '1',
                            'name' => 'base',
                            'price' => '10.80',
                            'currency' => 'EUR',
                        ),
                        1 =>
                        array(
                            'is_base_price' => '0',
                            'name' => 'Kortingstarief',
                            'price' => '9.80',
                            'currency' => 'EUR',
                        ),
                        2 =>
                        array(
                            'is_base_price' => '0',
                            'name' => 'Kinepolis Student Card',
                            'price' => '8.10',
                            'currency' => 'EUR',
                        ),
                )
            );

        $expected = new StringLiteral('[{"category":"base","name":{"nl":"Basistarief"},"price":10.8,"priceCurrency":"EUR"},{"category":"tariff","name":{"nl":"Kortingstarief"},"price":9.8,"priceCurrency":"EUR"},{"category":"tariff","name":{"nl":"Kinepolis Student Card"},"price":8.1,"priceCurrency":"EUR"}]');
        $actual = $this->formatter->formatPrice($externalIdMovie);
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function testFormatProduction()
    {
        $externalIdProduction = new StringLiteral('Testm4321');

        $eventList = array(
            0 =>
                array(
                    'cdbid_event' => 'ba9d9202-d79d-44c0-ae16-edfbd4736d78',
                ),
                1 =>
                array(
                    'cdbid_event' => '0d47e21c-34e7-445c-aa6a-8b0ae5779fda',
                ),
                2 =>
                array(
                    'cdbid_event' => '9bb00339-0232-48cc-974b-5039a2b29fbb',
                ),
        );

        $this->repository->expects($this->once())
            ->method('getProductionCdbid')
            ->with($externalIdProduction)
            ->willReturn(new StringLiteral('12c707ae-1367-49ef-a297-194f79c84193'));

        $this->repository->expects($this->once())
            ->method('getName')
            ->with($externalIdProduction)
            ->willReturn(new StringLiteral('TEST PRODUCTION'));

        $this->repository->expects($this->once())
            ->method('getDescription')
            ->with($externalIdProduction)
            ->willReturn(new StringLiteral('DESC PRODUCTION'));

        $this->repository->expects($this->once())
            ->method('getCdbids')
            ->with($externalIdProduction)
            ->willReturn($eventList);

        $this->repository->expects($this->once())
            ->method('getThemeId')
            ->with($externalIdProduction)
            ->willReturn(new StringLiteral('1.7.6.0.0'));


        $expected = new StringLiteral(
            '<?xml version="1.0" encoding="utf-8"?>' .
            PHP_EOL .
            '<cdbxml xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="http://www.cultuurdatabank.com/XMLSchema/CdbXSD/3.3/FINAL"><production availablefrom="2018-01-01T00:00:00" availableto="2099-12-31T00:00:00" creationdate="2018-01-01T00:00:00" cdbid="12c707ae-1367-49ef-a297-194f79c84193" externalid="Testm4321" createdby="imports@cultuurnet.be" lastupdatedby="imports@cultuurnet.be"><categories><category catid="0.50.6.0.0" type="eventtype">Film</category><category catid="1.7.6.0.0" type="theme">Griezelfilm of horror</category></categories><productiondetails><productiondetail lang="nl"><shortdescription>DESC PRODUCTION</shortdescription><title>TEST PRODUCTION</title></productiondetail></productiondetails><relatedevents><id cdbid="ba9d9202-d79d-44c0-ae16-edfbd4736d78"/><id cdbid="0d47e21c-34e7-445c-aa6a-8b0ae5779fda"/><id cdbid="9bb00339-0232-48cc-974b-5039a2b29fbb"/></relatedevents></production></cdbxml>'
        );
        $actual = $this->formatter->formatProduction($externalIdProduction);
        $this->assertEquals($expected, $actual);

    }
}
