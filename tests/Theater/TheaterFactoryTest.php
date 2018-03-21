<?php

namespace CultuurNet\MovieApiFetcher\Theater;

use ValueObjects\Identity\UUID;

class TheaterFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TheaterFactory
     */
    private $theaterFactory;

    protected function setUp()
    {
        $theaters = array(
            'DECA' => array(
                'cdbid' => 'e9d3bd1c-ef93-4ea1-9d71-5058ab902823',
                'external_id' => 'ORG:782e2efb-a6c6-400c-a91b-bccc90319109',
            ),
            'KKOR' => array(
                'cdbid' => 'aa494524-944b-45cf-8668-ecd2e09f577a',
                'external_id' => 'ORG:e77ffdd7-aabb-4e09-8b70-078def3e74a5',
            ),
        );

        $this->theaterFactory = new TheaterFactory($theaters);
    }



    /**
     * @test
     */
    public function testTheaterMapping()
    {
        $expected = UUID::fromNative('aa494524-944b-45cf-8668-ecd2e09f577a');
        $this->assertEquals($expected, $this->theaterFactory->mapTheater('KKOR'));
    }
}
