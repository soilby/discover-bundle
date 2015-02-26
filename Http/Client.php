<?php
namespace Soil\DiscoverBundle\Http;

use EasyRdf\Http\Client as EasyRdfHttpClient;
use EasyRdf\Http\Response;

class Client extends EasyRdfHttpClient {
    public function request($method = null) {
        $firstResponse = parent::request($method);

        $br = PHP_EOL;
        $rawResponse = $firstResponse->getHeadersAsString(true, $br)
            . $br
            . mb_convert_encoding($firstResponse->getRawBody(), 'HTML-ENTITIES', 'UTF-8');

        return Response::fromString($rawResponse);
    }
} 