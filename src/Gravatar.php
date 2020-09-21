<?php

declare(strict_types=1);

namespace Baghayi\Gravatar;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class Gravatar
{
    private const IMAGE_URL   = 'https://www.gravatar.com/avatar/%s.jpg?d=%s%s';

    private $http;
    private $defaultImg;

    public function __construct(Client $http, string $defaultImg = '404')
    {
        $this->http = $http;
        $this->defaultImg = $defaultImg;
    }

    public function exists(string $email, bool $hashed = false): bool
    {
        try {
          return $this->http->head($this->imageUrl($email, 1, $hashed))->getStatusCode() === 200;
        } catch(RequestException $e) {
            return false;
        }
    }

    public function imageUrl(string $email, int $size = null, bool $hashed = false): string
    {
        $_size = '';
        if (!is_null($size)) {
            $_size = '&size=' . $size;
        }

        if($hashed){
            return sprintf(self::IMAGE_URL, $email, $this->defaultImg, $_size);
        }

        return sprintf(self::IMAGE_URL, $this->emailHash($email), $this->defaultImg, $_size);
    }

    private function emailHash(string $email): string
    {
        return md5(strtolower($email));
    }
}
