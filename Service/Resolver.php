<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 14.17
 */

namespace Soil\DiscoverBundle\Service;


use EasyRdf\Graph;
use EasyRdf\Http;
use EasyRdf\RdfNamespace;
use Soil\DiscoverBundle\Entity\Generic;
use Soil\DiscoverBundle\Http\Client;
use Soil\DiscoverBundle\Service\Exception\NothingLoadedException;
use Symfony\Bridge\Monolog\Logger;

class Resolver {

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Graph
     */
    protected $lastGraph;


    protected $entityFactory;

    protected $namespacesConfig;

    protected $localCache = [];

    public function __construct($entityFactory, $namespacesConfig) {
        $this->entityFactory = $entityFactory;

        $this->namespacesConfig = $namespacesConfig;

        foreach ($this->namespacesConfig as $namespace => $uri) {
            \EasyRdf\RdfNamespace::set($namespace, $uri);
        }

        $client = new Client();
        $client->setConfig([
            'timeout' => 100
        ]);

        Http::setDefaultHttpClient($client);
    }
    
    
    public function getDocument($uri)   {
        $graph = new Graph();
        $number = $graph->load($uri, 'jsonld');
        if ($number === 0)  {
            throw new NothingLoadedException('Nothing loaded for URI: ' . $uri);
        }
        
        return $graph;
    }
    


    /**
     * @param $uri
     * @param null $getFirstOfClass
     * @return array|Generic
     * @throws NothingLoadedException
     * @throws \EasyRdf\Exception
     * @throws \EasyRdf\Http\Exception
     */
    public function getEntityForURI($uri, $getFirstOfClass = null)   {

        $this->logger->addInfo('Try to discover ' . $uri);
        $cacheKey = md5($uri . $getFirstOfClass);

        if (array_key_exists($cacheKey, $this->localCache)) {
            $this->logger->addAlert('Return from cache!');
            return $this->localCache[$cacheKey];
        }

        if ($getFirstOfClass)   {
            if ($getFirstOfClass === true)  {
                $this->logger->addInfo('Caller expect first founded entity');
            }
            else    {
                $this->logger->addInfo('Caller expect entity of class ' . $getFirstOfClass);
            }
        }


        $graph = new Graph();
        $number = $graph->load($uri);
        if ($number === 0)  {
            throw new NothingLoadedException('Nothing loaded for URI: ' . $uri);
        }
        
        $this->lastGraph = $graph;
        
        $fetchedEntities = [];

        $resources = $graph->resources();
        $this->logger->addInfo('Resources found: ' . count($resources));

        foreach ($resources as $resource)  {
            $types = ($type = $resource->type()) ? [$type] : [];

            $additionalTypeResource = $resource->getResource('schema:additionalType');
//            $s = print_r($additionalTypeResource, true);
//            $this->logger->addInfo($s);

            if ($additionalTypeResource)    {
                $additionalType = $additionalTypeResource->getUri();
//                $this->logger->addInfo($additionalType);
//                $this->logger->addInfo(RdfNamespace::shorten($additionalType));

                $types[] = RdfNamespace::shorten($additionalType);
            }

            $types = array_unique(array_merge($types, $resource->types()));

            $this->logger->addInfo('Resource types: ' . implode(', ', $types));

            if (empty($types)) continue;

            $props = [];
            foreach ($resource->properties() as $propertyName)  {
                $propertyValue = $resource->get($propertyName);
                $props[$propertyName] = $propertyValue;
            }

            $props['_origin'] = $uri;

            if ($getFirstOfClass)   { //hook for filter fetched entities

                if (is_string($getFirstOfClass)) {
                    $classSpec = $this->entityFactory->detectEntityClass($types, $uri);

                    if ($classSpec['className'] !== $getFirstOfClass) {
                        $this->logger->addInfo('Skip entitySkip entity ' . $classSpec['className']);
                        continue;
                    }

                    $this->logger->addInfo('Found expected entity');

                    //return only one entity with specified type
                }

                $entity = $this->entityFactory->factory($types, $props);
                $this->localCache[$cacheKey] = $entity;

                return $entity;

            }
            else    {
                $fetchedEntities[] =  $this->entityFactory->factory($types, $props);
            }
        }

        if ($getFirstOfClass)   {
            throw new NothingLoadedException("Required entity not found");
        }

        $this->logger->addInfo('Fetched entities: ' . count($fetchedEntities));

        $this->localCache[$cacheKey] = $fetchedEntities;

        return $fetchedEntities;


    }


    public function setLogger($logger)  {
        $this->logger = $logger;
    }

    /**
     * @return Graph
     */
    public function getLastGraph()
    {
        return $this->lastGraph;
    }
    
    
}