<?php
namespace Soil\DiscoverBundle\Entity;
use EasyRdf\Literal;
use EasyRdf\Resource;

/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 11.44
 */

class Generic {

    protected $iriMap = [
        'schema:name' => 'name',
        '_origin' => '_origin'
    ];

    /**
     * Origin values
     * @var array
     */
    protected $origin = [];


    public static function support($type)    {
        return true;
    }

    public function __construct($type, $props) {
        $this->build($props);
    }

    public function build($props)    {
        $this->origin = $props;

        foreach ($props as $propName => $propValue) {
            if (array_key_exists($propName, $this->iriMap)) {
                $parsedPropName = $this->iriMap[$propName];
                $value = $this->transformValue($propValue, $parsedPropName);
                $this->$parsedPropName = $value;
            }
            else    {
                $value = $this->transformValue($propValue, $propName);
                $this->$propName = $value;
            }

        }
    }

    protected function transformValue($value, $name)    {
        switch (true)   {
            case is_scalar($value) || is_array($value):
                return $value;

            case $value instanceof Resource:
                return $value->getUri();

            case method_exists($value, '__toString'): //for Literal
                return (string) $value;

            default:
                return $value;

        }
    }

    public function getOriginValue($originPropName, $default = null)    {
        if (array_key_exists($originPropName, $this->origin)) {
            return $this->origin[$originPropName];
        }

        return $default;
    }

    public function getOriginPropName($parsedPropName)  {
        return array_search($parsedPropName, $this->iriMap);
    }


    /**
     * Add access by origin names
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)    {
        if (array_key_exists($name, $this->iriMap)) {
            $parsedName = $this->iriMap[$name];

            return $this->$parsedName;
        }
    }
} 