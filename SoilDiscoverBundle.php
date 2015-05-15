<?php

namespace Soil\DiscoverBundle;

use EasyRdf\Http;
use Soil\DiscoverBundle\Http\Client;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SoilDiscoverBundle extends Bundle
{
    public function boot()
    {
        $httpClient = new Client();
        Http::setDefaultHttpClient($httpClient);

    }
}
