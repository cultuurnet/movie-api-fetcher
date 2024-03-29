<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Theater;

use PHPUnit\Framework\TestCase;
use ValueObjects\Identity\UUID;

class TheaterFactoryTest extends TestCase
{
    private TheaterFactory $theaterFactory;

    protected function setUp(): void
    {
        $theaters = [
            'DECA' => [
                'cdbid' => 'e9d3bd1c-ef93-4ea1-9d71-5058ab902823',
                'external_id' => 'ORG:782e2efb-a6c6-400c-a91b-bccc90319109',
            ],
            'KKOR' => [
                'cdbid' => 'aa494524-944b-45cf-8668-ecd2e09f577a',
                'external_id' => 'ORG:e77ffdd7-aabb-4e09-8b70-078def3e74a5',
            ],
        ];

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
