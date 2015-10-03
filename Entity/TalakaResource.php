<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 8.5.15
 * Time: 14.30
 */

namespace Soil\DiscoverBundle\Entity;

use Soil\DiscoverBundle\Annotation as RDF;

/**
 * Class TalakaProject
 * @package Soil\DiscoverBundle\Entity
 * @RDF\Iri("tal:TalakaResource")
 */
class TalakaResource extends Generic {


    /**
     * @var
     * @RDF\Iri("tal:author")
     */
    protected $author;


    /**
     * @var
     * @RDF\Iri(value="schema:alternateName", persist=false)
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