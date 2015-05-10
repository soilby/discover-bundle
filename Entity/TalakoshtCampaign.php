<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 8.5.15
 * Time: 14.30
 */

namespace Soil\DiscoverBundle\Entity;


class TalakoshtCampaign extends Generic {

    public static function support($type)    {
        return $type === 'tal:TalakoshtCampaign';
    }


    public function __construct($type, $properties)   {

        switch ($type)  {
            case 'tal:TalakoshtCampaign':
                $fields = [
                    'schema:alternateName' => 'id',
                    'schema:name' => 'name',
                    'schema:author' => 'author',
                    'schema:image' => 'image',
                ];

                break;

            default:
                $fields = [];
        }

        $this->iriMap = array_merge($this->iriMap, $fields);

        parent::__construct($type, $properties);
    }
} 