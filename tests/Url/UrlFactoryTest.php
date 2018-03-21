<?php

namespace CultuurNet\MovieApiFetcher\Url;

use ValueObjects\StringLiteral\StringLiteral;
use ValueObjects\Web\Url;

class UrlFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var StringLiteral
     */
    private $baseUrl;

    /**
     * @var UrlFactory
     */
    private $urlFactory;

    protected function setUp()
    {
        $this->baseUrl = new StringLiteral('https://kinepolis.dev/nl/');

        $this->urlFactory = new UrlFactory($this->baseUrl);
    }

    /**
     * @test
     */
    public function itGeneratesATokenUrl()
    {
        $url = $this->urlFactory->generateTokenUrl();

        $expectedUrl = Url::fromNative(
            $this->baseUrl . 'services/jwt/1.0/token'
        );

        $this->assertEquals($expectedUrl, $url);
    }

    /**
     * @test
     */
    public function itGeneratesAMoviesUrl()
    {
        $url = $this->urlFactory->generateMoviesUrl();

        $expectedUrl = Url::fromNative(
            $this->baseUrl . 'services/content/1.1/movies?progList=2'
        );

        $this->assertEquals($expectedUrl, $url);
    }

    /**
     * @test
     */
    public function itGeneratesAMoviedetailUrl()
    {
        $mid = rand(1, 64000);
        $url = $this->urlFactory->generateMovieDetailUrl($mid);

        $expectedUrl = Url::fromNative(
            $this->baseUrl . 'services/content/1.1/movies/' . $mid
        );

        $this->assertEquals($expectedUrl, $url);
    }

    /**
     * @test
     */
    public function itGeneratesAMediaUrl()
    {
        $mediaLink = new StringLiteral('/test.jpg');
        $url = $this->urlFactory->generateMediaUrl($mediaLink);

        $expectedUrl = Url::fromNative(
            $this->baseUrl . 'sites/kinepolis.be.nl/files' . $mediaLink->toNative()
        );

        $this->assertEquals($expectedUrl, $url);
    }

    /**
     * @test
     */
    public function itGeneratesATheaterUrl()
    {
        $url = $this->urlFactory->generateTheatreUrl();

        $expectedUrl = Url::fromNative(
            $this->baseUrl . 'services/content/1.1/theaters'
        );

        $this->assertEquals($expectedUrl, $url);
    }
}
