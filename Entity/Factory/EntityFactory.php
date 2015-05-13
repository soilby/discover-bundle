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

class EntityFactory {

    /**
     * @var Logger
     */
    protected $logger;


    protected $entityClassesMap = [];

    public function __construct($entityClassesMap)  {
        if (is_array($entityClassesMap)) {
            $this->entityClassesMap = $entityClassesMap;
        }
    }

    public function detectEntityClass($types)    {
        if (is_scalar($types)) $types = [$types];

        foreach ($types as $type) {

            if (!array_key_exists($type, $this->entityClassesMap)) continue;

            $className = $this->entityClassesMap[$type];

            return [
                'className' => $className,
                'type' => $type
            ];
        }

        reset($types);
        return [
            'className' => 'Soil\DiscoverBundle\Entity\Generic',
            'type' => current($types)
        ];
    }

    public function factory($type, $fields)   {
        $classSpec = $this->detectEntityClass($type);
        if (!$classSpec)    {
            return null;
        }

        $className = $classSpec['className'];
        $type = $classSpec['type'];

        $object = new $className($type);

        $annotationReader = new AnnotationReader();

        $reflectionClass = new \ReflectionClass($object);

        $properties = $reflectionClass->getProperties();


        $fieldsMap = [];
        foreach ($properties as $reflectionProperty)    {

            $matchAnnotation = $annotationReader->getPropertyAnnotation($reflectionProperty, 'Soil\DiscoverBundle\Annotation\Match');

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
                $object->$propertyName = $value;
            }

        }

        return $object;
    }



    public function setLogger($logger)  {
        $this->logger = $logger;
    }

}