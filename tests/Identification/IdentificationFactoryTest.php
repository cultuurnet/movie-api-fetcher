<?php

namespace CultuurNet\MovieApiFetcher\Identification;

use ValueObjects\StringLiteral\StringLiteral;

class IdentificationFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var IdentificationFactory
     */
    private $identificationFactory;

    protected function setUp()
    {
        $this->identificationFactory = new IdentificationFactory();
    }

    /**
     * @test
     */
    public function testGenerateMovieProductionId()
    {
        $expected = new StringLiteral('Kinepolis:m63210');
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
        $expected = new StringLiteral('Kinepolis:tDECAm63210');
        $this->assertEquals(
            $expected,
            $this->identificationFactory->generateMovieId('63210', 'DECA', '2D')
        );
    }
}
