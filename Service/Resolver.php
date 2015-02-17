<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 14.17
 */

namespace Soil\DiscoverBundle\Service;


use EasyRdf\Graph;

class Resolver {


    public function getEntityForURI($uri)   {
        $graph = new Graph();
        $graph->load($uri);

        foreach ($graph->resources() as $resource)  {

            echo $resource->dump('text');
        }


    }
} 