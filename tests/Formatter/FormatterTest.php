<?php

declare(strict_types=1);

namespace CultuurNet\MovieApiFetcher\Formatter;

use CultuurNet\TransformEntryStore\Stores\RepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FormatterTest extends TestCase
{
    private Formatter $formatter;

    /**
     * @var RepositoryInterface|MockObject
     */
    private $repository;

    private string $url;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(RepositoryInterface::class);
        $this->url = 'https://io-dev.uitdatabank.dev/';

        $this->formatter = new Formatter($this->repository, $this->url);
    }

    /**
     * @test
     */
    public function testFormatPrice(): void
    {
        $externalIdMovie = 'Testm4321';

        $this->repository->expects($this->once())
            ->method('getPrice')
            ->with($externalIdMovie)
            ->willReturn(
                [
                        0 =>
                        [
                            'is_base_price' => '1',
                            'name' => 'base',
                            'price' => '10.80',
                            'currency' => 'EUR',
                        ],
                        1 =>
                        [
                            'is_base_price' => '0',
                            'name' => 'Kortingstarief',
                            'price' => '9.80',
                            'currency' => 'EUR',
                        ],
                        2 =>
                        [
                            'is_base_price' => '0',
                            'name' => 'Kinepolis Student Card',
                            'price' => '8.10',
                            'currency' => 'EUR',
                        ],
                ]
            );

        $expected = '[{"category":"base","name":{"nl":"Basistarief"},"price":10.8,"priceCurrency":"EUR"},{"category":"tariff","name":{"nl":"Kortingstarief"},"price":9.8,"priceCurrency":"EUR"},{"category":"tariff","name":{"nl":"Kinepolis Student Card"},"price":8.1,"priceCurrency":"EUR"}]';
        $actual = $this->formatter->formatPrice($externalIdMovie);
        $this->assertEquals($expected, $actual);
    }
}
