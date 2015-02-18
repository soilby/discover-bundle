<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 21.25
 */

namespace Soil\DiscoverBundle\Entity\Factory;

class EntityFactory {
    protected $entityClassesMap = [];

    public function __construct($entityClassesMap)  {
        if (is_array($entityClassesMap)) {
            $this->entityClassesMap = $entityClassesMap;
        }
    }

    public function detectEntityClass($types)    {
        if (is_scalar($types)) $types = [$types];

        foreach ($types as $type) {
            foreach ($this->entityClassesMap as $className) {
                $callable = [$className, 'support'];

                if (!is_callable($callable)) {
                    throw new \Exception("Entities should implement static support method");
                }

                if (call_user_func($callable, $type)) {
                    return [
                        'className' => $className,
                        'type' => $type
                    ];
                }
            }
        }

        return false;
    }

    public function factory($type, $properties)   {
        $classSpec = $this->detectEntityClass($type);
        if (!$classSpec)    {
            return null;
        }

        $className = $classSpec['className'];
        $type = $classSpec['type'];

        return new $className($type, $properties);
    }

} 