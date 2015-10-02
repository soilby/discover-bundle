<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 8.5.15
 * Time: 14.30
 */

namespace Soil\DiscoverBundle\Entity;

use Soil\DiscoverBundle\Annotation as RDF;

class TalakaProject extends Generic {


    protected $image;

    protected $name;

    /**
     * @var
     * @RDF\Match("schema:author")
     */
    protected $author;


    /**
     * @var
     * @RDF\Match("schema:alternateName")
     */
    protected $id;



    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    }



}