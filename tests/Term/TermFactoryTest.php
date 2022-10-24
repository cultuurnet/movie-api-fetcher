<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Term;

use PHPUnit\Framework\TestCase;

class TermFactoryTest extends TestCase
{
    /**
     * @var TermFactory
     */
    private $termFactory;

    protected function setUp(): void
    {
        $terms = [
            636 => '1.7.6.0.0',
            634 => '1.7.7.0.0',
            635 => '1.7.15.0.0',
        ];

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
