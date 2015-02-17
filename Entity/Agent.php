<?php
namespace Soil\NotificationBundle\Entity;

/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 11.42
 */

class Agent extends \Soil\NotificationBundle\Entity\Generic {

    public function __construct()   {
        $this->iriMap = array_merge($this->iriMap, [
            'og:image' => 'image',
            'foaf:firstName' => 'firstName',
            'foaf:lastName' => 'lastName',
            'foaf:name' => 'name',
            'foaf:mbox' => 'mbox',
        ]);
    }


    public $mbox;
    public $firstName;
    public $lastName;
    public $displayName;

    public $uri;



} 