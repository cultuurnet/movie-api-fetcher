<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Identification;

use PHPUnit\Framework\TestCase;

class IdentificationFactoryTest extends TestCase
{
    private IdentificationFactory $identificationFactory;

    protected function setUp(): void
    {
        $this->identificationFactory = new IdentificationFactory();
    }

    /**
     * @test
     */
    public function testGenerateMovieProductionId()
    {
        $expected = 'Kinepolis:m63210';
        $this->assertEquals(
            $expected,
            $actual = $this->identificationFactory->generateMovieProductionId('63210')
        );
    }

    /**
     * @test
     */
    public function testGenerateMovieId()
    {
        $expected = 'Kinepolis:tDECAm63210';
        $this->assertEquals(
            $expected,
            $this->identificationFactory->generateMovieId('63210', 'DECA', '2D')
        );
    }
}
