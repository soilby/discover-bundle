<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 21.25
 */

namespace Soil\DiscoverBundle\Entity\Factory;

use Doctrine\Common\Annotations\AnnotationReader;
use EasyRdf\Literal;
use EasyRdf\Literal\Date;
use EasyRdf\Literal\DateTime;
use EasyRdf\Resource;
use Monolog\Logger;
use Soil\EventProcessorBundle\Service\DeURInator;

class EntityFactory {

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var DeURInator
     */
    protected $deURInator;

    protected $entityClassesMap = [];

    public function __construct($entityClassesMap)  {
        if (is_array($entityClassesMap)) {
            $this->entityClassesMap = $entityClassesMap;
        }
    }

    public function detectEntityClass($types, $uri)    {
        if (is_scalar($types)) $types = [$types];

        $info = [];

        $uriSpec = $this->deURInator->parseUri($uri);

        if ($uriSpec)   {
            $info['parsedNamespace'] = $uriSpec['parsedType'];
            $info['namespace'] = $uriSpec['type'];
            $info['uniquePart'] = $uriSpec['id'];
        }
        else    {
            $info['parsedNamespace'] = null;
            $info['namespace'] = null;
            $info['uniquePart'] = null;
        }

//var_dump($this->entityClassesMap);
        foreach ($types as $type) {

            if (!array_key_exists($type, $this->entityClassesMap)) continue;

            $className = $this->entityClassesMap[$type];

            $info['className'] = $className;
            $info['type'] = $type;

            if (!$info['parsedNamespace'])  {
                if (property_exists($className, 'talakaNamespace')) {
                    $parsedNamespace = $className::$talakaNamespace;
                    $info['parsedNamespace'] = $parsedNamespace;
                }
            }

            if (!$info['parsedNamespace'])  {
                $info['parsedNamespace'] = 'tal';
            }

            return $info;
        }

        reset($types);

        $info['type'] = current($types);
        $info['className'] = 'Soil\DiscoverBundle\Entity\Generic';

        return $info;
    }

    public function factory($type, $fields)   {
        $classSpec = $this->detectEntityClass($type, $fields['_origin']);
        if (!$classSpec)    {
            return null;
        }

        $className = $classSpec['className'];
        $type = $classSpec['type'];

        $object = new $className($type);

        $annotationReader = new AnnotationReader();

        $reflectionClass = new \ReflectionClass($object);

        $properties = $reflectionClass->getProperties();

        if ($reflectionClass->hasProperty('rdfNamespace')) {
            $namespaceProp = $reflectionClass->getProperty('rdfNamespace');
            $namespaceProp->setAccessible(true);
            $namespaceProp->setValue($object, $classSpec['parsedNamespace']);
        }


        $fieldsMap = [];
        foreach ($properties as $reflectionProperty)    {

            $matchAnnotation = $annotationReader->getPropertyAnnotation($reflectionProperty, 'Soil\DiscoverBundle\Annotation\Iri');

            if ($matchAnnotation)    {
                $match = $matchAnnotation->value;

                $fieldsMap[$match] = $reflectionProperty->getName();
            }
        }

        foreach ($fields as $field => $value)   {
            if (array_key_exists($field, $fieldsMap))   {
                $propertyName = $fieldsMap[$field];
            }
            else {
                $propertyName = substr($field, strpos($field, ':') + 1);

                if (!($reflectionClass->hasProperty($propertyName) || $reflectionClass->hasMethod('__set')))  {
                    continue;
                }
            }

//var_dump($field);
//            if (is_object($value)) var_dump(get_class($value)); else var_dump($value);

            //detect value type
            switch (true)   {
                case is_scalar($value) || is_array($value):
                case $value instanceof Literal:
                    break;

                case $value instanceof Resource:
                    if ($value->type()) {

                        $nestedProps = [];
                        foreach ($value->properties() as $nestedPropertyName)  {
                            $nestedPropertyValue = $value->get($nestedPropertyName);
                            $nestedProps[$nestedPropertyName] = $nestedPropertyValue;
                        }

                        $nestedProps['_origin'] = $value->getUri();

                        $value = $this->factory($value->types(), $nestedProps);

                    }
                    else    {
                        $value = $value->getUri();
                    }

                    break;

                default:
                    break;

            }

            if ($reflectionClass->hasProperty($propertyName))   {
                $property = $reflectionClass->getProperty($propertyName);
                $property->setAccessible(true);
                $property->setValue($object, $value);
            }
            else    {
                //via set method
//                echo $propertyName;
//                var_dump($value);
                $object->$propertyName = $value;
            }

        }

        return $object;
    }



    public function setLogger($logger)  {
        $this->logger = $logger;
    }

    /**
     * @param mixed $deURInator
     */
    public function setDeURInator($deURInator)
    {
        $this->deURInator = $deURInator;
    }





}