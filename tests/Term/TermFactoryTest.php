<?php

namespace CultuurNet\MovieApiFetcher\Term;

class TermFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TermFactory
     */
    private $termFactory;

    protected function setUp()
    {
        $terms = array(
            636 => '1.7.6.0.0',
            634 => '1.7.7.0.0',
            635 => '1.7.15.0.0',
        );

        $this->termFactory = new TermFactory($terms);
    }

    /**
     * @test
     */
    public function testTermMapping()
    {
        $expected = '1.7.7.0.0';
        $this->assertEquals($expected, $this->termFactory->mapTerm(634));
    }
}
