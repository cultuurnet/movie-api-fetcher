<?php

namespace CultuurNet\MovieApiFetcher\Authentication;

use CultuurNet\MovieApiFetcher\Url\UrlFactoryInterface;

class AuthenticationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Authentication
     */
    private $authentication;

    /**
     * @var UrlFactoryInterface
     */
    private $urlFactory;

    protected function setUp()
    {
        $this->urlFactory = $this->createMock(UrlFactoryInterface::class);

        $this->authentication = new Authentication($this->urlFactory);
    }

    /**
     * @test
     */
    public function testGeneratePostBody()
    {

    }
}
