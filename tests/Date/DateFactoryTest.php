<?php

namespace CultuurNet\MovieApiFetcher\Date;

class DateFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DateFactory
     */
    private $dateFactory;

    private $dates;

    private $timeTable;

    private $length;

    protected function setUp()
    {
        $this->dateFactory = new DateFactory();
        $this->dates = array(
            '2018-03-28' =>
                array(
                    0 =>
                        array(
                            'prid' => 1506932,
                            'tid' => 'METRO',
                            'screen' => 9,
                            'time' => '13:45:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        1 =>
                        array(
                            'prid' => 1515217,
                            'tid' => 'SL',
                            'screen' => 2,
                            'time' => '16:30:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        2 =>
                        array(
                            'prid' => 1506930,
                            'tid' => 'KOOST',
                            'screen' => 6,
                            'time' => '16:45:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        3 =>
                        array(
                            'prid' => 1507162,
                            'tid' => 'KKOR',
                            'screen' => 6,
                            'time' => '16:45:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        4 =>
                        array(
                            'prid' => 1506929,
                            'tid' => 'KBRG',
                            'screen' => 7,
                            'time' => '17:00:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        5 =>
                        array(
                            'prid' => 1506933,
                            'tid' => 'METRO',
                            'screen' => 9,
                            'time' => '17:00:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        6 =>
                        array(
                            'prid' => 1507159,
                            'tid' => 'KHAS',
                            'screen' => 2,
                            'time' => '17:15:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        7 =>
                        array(
                            'prid' => 1507158,
                            'tid' => 'DECA',
                            'screen' => 8,
                            'time' => '20:00:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        8 =>
                        array(
                            'prid' => 1506931,
                            'tid' => 'METRO',
                            'screen' => 9,
                            'time' => '20:15:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        9 =>
                        array(
                            'prid' => 1506927,
                            'tid' => 'KBRAI',
                            'screen' => 9,
                            'time' => '20:30:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        10 =>
                        array(
                            'prid' => 1507160,
                            'tid' => 'KHAS',
                            'screen' => 2,
                            'time' => '20:30:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                ),
                '2018-03-29' =>
                array(
                    0 =>
                        array(
                            'prid' => 1506942,
                            'tid' => 'SL',
                            'screen' => 2,
                            'time' => '16:20:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        1 =>
                        array(
                            'prid' => 1506939,
                            'tid' => 'KOOST',
                            'screen' => 6,
                            'time' => '16:45:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        2 =>
                        array(
                            'prid' => 1507168,
                            'tid' => 'KKOR',
                            'screen' => 6,
                            'time' => '16:45:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        3 =>
                        array(
                            'prid' => 1506938,
                            'tid' => 'KBRG',
                            'screen' => 7,
                            'time' => '17:00:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        4 =>
                        array(
                            'prid' => 1506940,
                            'tid' => 'METRO',
                            'screen' => 9,
                            'time' => '17:00:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        5 =>
                        array(
                            'prid' => 1507165,
                            'tid' => 'KHAS',
                            'screen' => 2,
                            'time' => '17:15:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        6 =>
                        array(
                            'prid' => 1507164,
                            'tid' => 'DECA',
                            'screen' => 7,
                            'time' => '20:00:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        7 =>
                        array(
                            'prid' => 1506941,
                            'tid' => 'METRO',
                            'screen' => 9,
                            'time' => '20:15:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        8 =>
                        array(
                            'prid' => 1506936,
                            'tid' => 'KBRAI',
                            'screen' => 9,
                            'time' => '20:30:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                        9 =>
                        array(
                            'prid' => 1507166,
                            'tid' => 'KHAS',
                            'screen' => 2,
                            'time' => '20:30:00',
                            'variant' => 'OV',
                            'version' =>
                                array(
                                    0 => 598,
                                ),
                                'sub' =>
                                array(
                                    0 => 464,
                                    1 => 378,
                                ),
                                'format' =>
                                array(
                                    0 => 52,
                                ),
                        ),
                ),
        );
        $this->timeTable = array(
            'METRO' =>
                array(
                    '3D' =>
                        array(
                            '2018-03-28' =>
                                array(
                                    0 =>
                                        array(
                                            0 => '13:45:00',
                                            1 => '16:05:00',
                                        ),
                                        1 =>
                                        array(
                                            0 => '17:00:00',
                                            1 => '19:20:00',
                                        ),
                                        2 =>
                                        array(
                                            0 => '20:15:00',
                                            1 => '22:35:00',
                                        ),
                                ),
                                '2018-03-29' =>
                                array(
                                    0 =>
                                        array(
                                            0 => '17:00:00',
                                            1 => '19:20:00',
                                        ),
                                        1 =>
                                        array(
                                            0 => '20:15:00',
                                            1 => '22:35:00',
                                        ),
                                ),
                        ),
                ),
                'SL' =>
                array(
                    '3D' =>
                        array(
                            '2018-03-28' =>
                                array(
                                    0 =>
                                        array(
                                            0 => '16:30:00',
                                            1 => '18:50:00',
                                        ),
                                ),
                                '2018-03-29' =>
                                array(
                                    0 =>
                                        array(
                                            0 => '16:20:00',
                                            1 => '18:40:00',
                                        ),
                                ),
                        ),
                ),
                'KOOST' =>
                array(
                    '3D' =>
                        array(
                            '2018-03-28' =>
                                array(
                                    0 =>
                                        array(
                                            0 => '16:45:00',
                                            1 => '19:05:00',
                                        ),
                                ),
                                '2018-03-29' =>
                                array(
                                    0 =>
                                        array(
                                            0 => '16:45:00',
                                            1 => '19:05:00',
                                        ),
                                ),
                        ),
                ),
                'KKOR' =>
                array(
                    '3D' =>
                        array(
                            '2018-03-28' =>
                                array(
                                    0 =>
                                        array(
                                            0 => '16:45:00',
                                            1 => '19:05:00',
                                        ),
                                ),
                                '2018-03-29' =>
                                array(
                                    0 =>
                                        array(
                                            0 => '16:45:00',
                                            1 => '19:05:00',
                                        ),
                                ),
                        ),
                ),
                'KBRG' =>
                array(
                    '3D' =>
                        array(
                            '2018-03-28' =>
                                array(
                                    0 =>
                                        array(
                                            0 => '17:00:00',
                                            1 => '19:20:00',
                                        ),
                                ),
                                '2018-03-29' =>
                                array(
                                    0 =>
                                        array(
                                            0 => '17:00:00',
                                            1 => '19:20:00',
                                        ),
                                ),
                        ),
                ),
                'KHAS' =>
                array(
                    '3D' =>
                        array(
                            '2018-03-28' =>
                                array(
                                    0 =>
                                        array(
                                            0 => '17:15:00',
                                            1 => '19:35:00',
                                        ),
                                        1 =>
                                        array(
                                            0 => '20:30:00',
                                            1 => '22:50:00',
                                        ),
                                ),
                                '2018-03-29' =>
                                array(
                                    0 =>
                                        array(
                                            0 => '17:15:00',
                                            1 => '19:35:00',
                                        ),
                                        1 =>
                                        array(
                                            0 => '20:30:00',
                                            1 => '22:50:00',
                                        ),
                                ),
                        ),
                ),
                'DECA' =>
                array(
                    '3D' =>
                        array(
                            '2018-03-28' =>
                                array(
                                    0 =>
                                        array(
                                            0 => '20:00:00',
                                            1 => '22:20:00',
                                        ),
                                ),
                                '2018-03-29' =>
                                array(
                                    0 =>
                                        array(
                                            0 => '20:00:00',
                                            1 => '22:20:00',
                                        ),
                                ),
                        ),
                ),
                'KBRAI' =>
                array(
                    '3D' =>
                        array(
                            '2018-03-28' =>
                                array(
                                    0 =>
                                        array(
                                            0 => '20:30:00',
                                            1 => '22:50:00',
                                        ),
                                ),
                                '2018-03-29' =>
                                array(
                                    0 =>
                                        array(
                                            0 => '20:30:00',
                                            1 => '22:50:00',
                                        ),
                                ),
                        ),
                ),
        );
        $this->length = 140;
    }

    /**
     * @test
     */
    public function processDates()
    {
        $this->assertEquals($this->dateFactory->processDates($this->dates, $this->length), $this->dateFactory->processDates($this->dates, $this->length));
    }

    /**
     * @test
     */
    public function testProcessDay()
    {

    }
}
