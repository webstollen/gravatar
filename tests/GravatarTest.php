<?php

namespace tests;

use Baghayi\Gravatar\Gravatar;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;

class GravatarTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
    * @test
    */
    public function says_profile_exists_on_an_email_which_has_profilecreated_for()
    {
        $emailHavingProfileInGaravatar = "hossein.*@gmail.com";

        $response = Mockery::mock(ResponseInterface::class);
        $response->allows()->getStatusCode()->andReturns(200);

        $httpClient = Mockery::mock(Client::class);
        $httpClient->allows()->head()->with(Mockery::any())->andReturns($response);

        $gravatar = new Gravatar($httpClient);
        $this->assertTrue($gravatar->exists($emailHavingProfileInGaravatar));
    }

    /**
    * @test
    */
    public function says_not_exists_on_an_unknown_email_whick_has_no_profile_created_for()
    {
        $emailHavingProfileInGaravatar = "unknown.email.to.gravatar@gmail.com";

        $httpClient = Mockery::mock(Client::class);
        $httpClient->allows()->head()->with(Mockery::any())
            ->andThrows(Mockery::mock(RequestException::class));

        $gravatar = new Gravatar($httpClient);
        $this->assertFalse($gravatar->exists($emailHavingProfileInGaravatar));
    }

    /**
    * @test
    */
    public function generates_images_url_gor_given_email()
    {
        $email = 'hossein@gmail.com';
        $gravatar = new Gravatar(Mockery::mock(Client::class));
        $this->assertStringStartsWith('https', $gravatar->imageUrl($email));
        $this->assertContains(md5($email), $gravatar->imageUrl($email));
    }

    /**
    * @test
    */
    public function returns_image_url_for_given_size()
    {
        $email = 'hossein@gmail.com';
        $requiredSize = 500;
        $gravatar = new Gravatar(Mockery::mock(Client::class));
        $this->assertContains('500', $gravatar->imageUrl($email, $requiredSize));
        $this->assertContains('size', $gravatar->imageUrl($email, $requiredSize));
    }

    /**
    * @test
    */
    public function returns_image_url_in_default_size_when_not_explicitly_specified()
    {
        $email = 'hossein@gmail.com';
        $gravatar = new Gravatar(Mockery::mock(Client::class));
        $this->assertNotContains('500', $gravatar->imageUrl($email));
        $this->assertNotContains('size', $gravatar->imageUrl($email));
    }
}
