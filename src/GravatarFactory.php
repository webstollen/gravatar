<?php

declare(strict_types=1);

namespace Baghayi\Gravatar;

use GuzzleHttp\Client;
use Psr\Container\ContainerInterface;

class GravatarFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $http = $container->get(Client::class) ?? new Client;
        return new Gravatar($http);
    }
}
