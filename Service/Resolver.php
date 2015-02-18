<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 14.17
 */

namespace Soil\DiscoverBundle\Service;


use EasyRdf\Graph;
use EasyRdf\RdfNamespace;

class Resolver {

    protected $entityFactory;

    public function __construct($entityFactory) {
        $this->entityFactory = $entityFactory;
    }


    public function getEntityForURI($uri, $getFirstOfClass = null)   {
        $graph = new Graph();
        $number = $graph->load($uri);
        if ($number === 0)  {
            throw new \Exception('Nothing loaded');
        }

        $fetchedEntities = [];

        foreach ($graph->resources() as $resource)  {
            $types = ($type = $resource->type()) ? [$type] : [];


            $additionalTypeResource = $resource->get('schema:additionalType');
            if ($additionalTypeResource)    {
                $additionalType = $additionalTypeResource->getUri();
                $types[] = RdfNamespace::shorten($additionalType);
            }

            $types = array_unique(array_merge($types, $resource->types()));

            if (empty($types)) continue;

            $props = [];
            foreach ($resource->properties() as $propertyName)  {
                $propertyValue = $resource->get($propertyName);
                $props[$propertyName] = $propertyValue;
            }

            $props['_origin'] = $uri;

            if ($getFirstOfClass)   { //hook for filter fetched entities
                if (is_string($getFirstOfClass)) {
                    $classSpec = $this->entityFactory->detectEntityClass($types);
                    if ($classSpec['className'] !== $getFirstOfClass) continue;

                    //return only one entity with specified type
                }

                return $this->entityFactory->factory($types, $props);

            }
            else    {
                $fetchedEntities[] =  $this->entityFactory->factory($types, $props);
            }
        }

        return $fetchedEntities;


    }
}