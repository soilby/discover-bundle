<?php
namespace Soil\DiscoverBundle\Http;

use EasyRdf\Http\Client as EasyRdfHttpClient;
use EasyRdf\Http\Response;

class Client extends EasyRdfHttpClient {
    public function request($method = null) {

        $uri = $this->getUri();
        
        $components = parse_url($uri);

        switch ($components['host'])    {
            case 'dev.talaka.by':
            case 'stage.talaka.by':
            case 'dev2.talaka.by':
                $username = "taluser";
                $password = "Qe8hExas";
                $basicAuthHash = base64_encode($username.":".$password);
    
                $this->setHeaders('Authorization', "Basic $basicAuthHash");
                
                break;
            
            case 'api.talaka.by':
            case 'dev.api.talaka.by':
            case 'test.api.talaka.by':
                $this->setParameterGet('token', '2d41c1824a8f7cdf0a8ae01f354b6056');
                break;

            default:
            case 'www.talaka.by':
            case 'talaka.by':
                break;
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