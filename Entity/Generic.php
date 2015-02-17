<?php
namespace Soil\NotificationBundle\Entity;
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 11.44
 */

class Generic {

    protected $iriMap = [

    ];


    public function __construct(\EasyRdf\Graph $graph) {
        $this->build($graph);
    }

    public function build($graph)    {
        $props = [];
        foreach ($graph->resources() as $resource)  {
            foreach ($resource->properties() as $propName)  {
                $value = $resource->get($propName);
                if (array_key_exists($propName, $this->iriMap)) {
                    $propName = $this->iriMap[$propName];
                }
                $props[$propName] = $value;
            }
        }
    }
} 