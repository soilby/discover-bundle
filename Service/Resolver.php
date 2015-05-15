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
use Soil\DiscoverBundle\Service\Exception\NothingLoadedException;
use Symfony\Bridge\Monolog\Logger;

class Resolver {

    /**
     * @var Logger
     */
    protected $logger;


    protected $entityFactory;

    protected $namespacesConfig;

    public function __construct($entityFactory, $namespacesConfig) {
        $this->entityFactory = $entityFactory;

        $this->namespacesConfig = $namespacesConfig;

        foreach ($this->namespacesConfig as $namespace => $uri) {
            \EasyRdf\RdfNamespace::set($namespace, $uri);
        }
    }


    public function getEntityForURI($uri, $getFirstOfClass = null)   {

        $this->logger->addInfo('Try to discover ' . $uri);

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

        $fetchedEntities = [];

        $resources = $graph->resources();
        $this->logger->addInfo('Resources found: ' . count($resources));

        foreach ($resources as $resource)  {
            $types = ($type = $resource->type()) ? [$type] : [];

            $additionalTypeResource = $resource->get('schema:additionalType');
            if ($additionalTypeResource)    {
                $additionalType = $additionalTypeResource->getUri();
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
                    $classSpec = $this->entityFactory->detectEntityClass($types);

                    if ($classSpec['className'] !== $getFirstOfClass) {
                        $this->logger->addInfo('Skip entitySkip entity ' . $classSpec['className']);
                        continue;
                    }

                    $this->logger->addInfo('Found expected entity');

                    //return only one entity with specified type
                }

                return $this->entityFactory->factory($types, $props);

                }
            else    {
                $fetchedEntities[] =  $this->entityFactory->factory($types, $props);
            }
        }

        $this->logger->addInfo('Fetched entities: ' . count($fetchedEntities));

        return $fetchedEntities;


    }


    public function setLogger($logger)  {
        $this->logger = $logger;
    }
}