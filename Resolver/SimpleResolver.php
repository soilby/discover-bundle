<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 7.6.16
 * Time: 15.21
 */

namespace Soil\DiscoverBundle\Resolver;


use Buzz\Client\Curl;
use Buzz\Message\Request;
use Buzz\Message\Response;
use Monolog\Logger;

class SimpleResolver
{
    /**
     * @var Logger
     */
    protected $logger;


    /**
     * @var Curl
     */
    protected $httpClient;

    public function __construct($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function resolve($uri)   {

        $request = new Request(Request::METHOD_GET, $uri);

        $response = new Response();
        $this->httpClient->send($request, $response);

        return $response;
    }

}