<?php
namespace Soil\DiscoverBundle\Entity;

/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 11.42
 */

class Comment extends Generic {

    public static function support($type)    {
        return $type === 'schema:UserComments';
    }

    public function __construct($type, $properties)   {

        switch ($type)  {
            case 'schema:UserComments':
                $fields = [
                    'tal:commentId' => 'id',
                    'schema:author' => 'author',
                    'schema:discusses' => 'entity',
                    'schema:commentText' => 'commentText',
                    'schema:commentTime' => 'creationDate',
                    'schema:eventStatus' => 'status',
                ];

                break;

            default:
                $fields = [];
        }

        $this->iriMap = array_merge($this->iriMap, $fields);

        parent::__construct($type, $properties);
    }

}