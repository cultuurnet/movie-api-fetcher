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
    public function it_generates_an_token_url()
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
    public function it_generates_a_movies_url()
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
    public function it_generates_a_moviedetail_url()
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
    public function it_generates_a_media_url()
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
    public function it_generates_a_theater_url()
    {
        $url = $this->urlFactory->generateTheatreUrl();

        $expectedUrl = Url::fromNative(
            $this->baseUrl . 'services/content/1.1/theaters'
        );

        $this->assertEquals($expectedUrl, $url);
    }
}
