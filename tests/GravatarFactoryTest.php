<?php

namespace tests;

use Baghayi\Gravatar\Gravatar;
use Baghayi\Gravatar\GravatarFactory;
use GuzzleHttp\Client;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class GravatarFactoryTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
    * @test
    */
    public function generates_Gravatar_instance_fetching_http_client_through_provided_container()
    {
        $container = Mockery::mock(ContainerInterface::class);
        $container->allows()->get(Client::class)->andReturns(Mockery::mock(Client::class));

        $factory = new GravatarFactory();
        $gravatarInstance = $factory($container);

        $this->assertInstanceOf(Gravatar::class, $gravatarInstance);
    }

    /**
    * @test
    */
    public function generates_gravatar_instance_creating_http_client_on_failing_to_fetch_http_from_container()
    {
        $container = Mockery::mock(ContainerInterface::class);
        $container->allows()->get(Client::class)->andReturns(null);

        $factory = new GravatarFactory();
        $gravatarInstance = $factory($container);

        $this->assertInstanceOf(Gravatar::class, $gravatarInstance);
    }
}
