<?php
namespace Soil\DiscoverBundle\Http;

use EasyRdf\Http\Client as EasyRdfHttpClient;
use EasyRdf\Http\Response;

class Client extends EasyRdfHttpClient {
    public function request($method = null) {

        $uri = $this->getUri();
        if (strpos($uri, 'http://talaka.by') === false && strpos($uri, 'http://www.talaka.by') === false)   {

            $username = "taluser";
            $password = "Qe8hExas";
            $basicAuthHash = base64_encode($username.":".$password);
            
            $this->setHeaders('Authorization', "Basic $basicAuthHash");
        }

        $firstResponse = parent::request($method);

        $br = PHP_EOL;
        $rawResponse = $firstResponse->getHeadersAsString(true, $br)
            . $br
            . mb_convert_encoding($firstResponse->getRawBody(), 'HTML-ENTITIES', 'UTF-8');
//        var_dump($rawResponse);
        return Response::fromString($rawResponse);
    }
} 