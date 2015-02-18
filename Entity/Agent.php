<?php
namespace Soil\DiscoverBundle\Entity;

/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 11.42
 */

class Agent extends Generic {

    public static function support($type)    {
        return strtolower($type) === 'foaf:person';
    }

    public function __construct($type, $properties)   {

        switch (strtolower($type))  {
            case 'foaf:person':
                $fields = [
                    'foaf:firstName' => 'firstName',
                    'foaf:lastName' => 'lastName',
                    'foaf:name' => 'displayName',
                    'foaf:mbox' => 'mbox',
                    'foaf:img' => 'img',
                ];

                break;

            default:
                $fields = [];
        }

        $this->iriMap = array_merge($this->iriMap, $fields);

        parent::__construct($type, $properties);
    }


    public $mbox;
    public $firstName;
    public $lastName;
    public $displayName;
    public $img;

    public $uri;


    /**
     * Transform mailto: URI to email address (crop mailto:)
     *
     * @param $value
     * @param $name
     *
     * @return string
     */
    protected function transformValue($value, $name)    {
        if ($name === 'mbox')   {
            $value = (string) $value;

            if (strpos($value, 'mailto:') === 0) {
                return substr($value, 7);
            }
            else    {
                return $value;
            }
        }
        else    {
            return parent::transformValue($value, $name);
        }
    }



} 