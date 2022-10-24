<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Date;

use PHPUnit\Framework\TestCase;

class DateFactoryTest extends TestCase
{
    private DateFactory $dateFactory;

    private $dates;

    private $length;

    protected function setUp(): void
    {
        $this->dateFactory = new DateFactory();
        $this->dates = [
            '2018-03-28' =>
                [
                    0 =>
                        [
                            'prid' => 1506932,
                            'tid' => 'METRO',
                            'screen' => 9,
                            'time' => '13:45:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        1 =>
                        [
                            'prid' => 1515217,
                            'tid' => 'SL',
                            'screen' => 2,
                            'time' => '16:30:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        2 =>
                        [
                            'prid' => 1506930,
                            'tid' => 'KOOST',
                            'screen' => 6,
                            'time' => '16:45:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        3 =>
                        [
                            'prid' => 1507162,
                            'tid' => 'KKOR',
                            'screen' => 6,
                            'time' => '16:45:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        4 =>
                        [
                            'prid' => 1506929,
                            'tid' => 'KBRG',
                            'screen' => 7,
                            'time' => '17:00:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        5 =>
                        [
                            'prid' => 1506933,
                            'tid' => 'METRO',
                            'screen' => 9,
                            'time' => '17:00:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        6 =>
                        [
                            'prid' => 1507159,
                            'tid' => 'KHAS',
                            'screen' => 2,
                            'time' => '17:15:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        7 =>
                        [
                            'prid' => 1507158,
                            'tid' => 'DECA',
                            'screen' => 8,
                            'time' => '20:00:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        8 =>
                        [
                            'prid' => 1506931,
                            'tid' => 'METRO',
                            'screen' => 9,
                            'time' => '20:15:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        9 =>
                        [
                            'prid' => 1506927,
                            'tid' => 'KBRAI',
                            'screen' => 9,
                            'time' => '20:30:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        10 =>
                        [
                            'prid' => 1507160,
                            'tid' => 'KHAS',
                            'screen' => 2,
                            'time' => '20:30:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                ],
                '2018-03-29' =>
                [
                    0 =>
                        [
                            'prid' => 1506942,
                            'tid' => 'SL',
                            'screen' => 2,
                            'time' => '16:20:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        1 =>
                        [
                            'prid' => 1506939,
                            'tid' => 'KOOST',
                            'screen' => 6,
                            'time' => '16:45:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        2 =>
                        [
                            'prid' => 1507168,
                            'tid' => 'KKOR',
                            'screen' => 6,
                            'time' => '16:45:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        3 =>
                        [
                            'prid' => 1506938,
                            'tid' => 'KBRG',
                            'screen' => 7,
                            'time' => '17:00:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        4 =>
                        [
                            'prid' => 1506940,
                            'tid' => 'METRO',
                            'screen' => 9,
                            'time' => '17:00:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        5 =>
                        [
                            'prid' => 1507165,
                            'tid' => 'KHAS',
                            'screen' => 2,
                            'time' => '17:15:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        6 =>
                        [
                            'prid' => 1507164,
                            'tid' => 'DECA',
                            'screen' => 7,
                            'time' => '20:00:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        7 =>
                        [
                            'prid' => 1506941,
                            'tid' => 'METRO',
                            'screen' => 9,
                            'time' => '20:15:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        8 =>
                        [
                            'prid' => 1506936,
                            'tid' => 'KBRAI',
                            'screen' => 9,
                            'time' => '20:30:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                        9 =>
                        [
                            'prid' => 1507166,
                            'tid' => 'KHAS',
                            'screen' => 2,
                            'time' => '20:30:00',
                            'variant' => 'OV',
                            'version' =>
                                [
                                    0 => 598,
                                ],
                                'sub' =>
                                [
                                    0 => 464,
                                    1 => 378,
                                ],
                                'format' =>
                                [
                                    0 => 52,
                                ],
                        ],
                ],
        ];

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
