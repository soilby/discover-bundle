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
 * Class TalakoshtCampaign
 * @package Soil\DiscoverBundle\Entity
 * @RDF\Iri("tal:TalakoshtCampaign")
 */
class TalakoshtCampaign extends TalakaProject {


    protected $campaignType;

    protected $image;

    protected $name;

    /**
     * @var
     * @RDF\Iri("schema:alternateName")
     */
    protected $id;



    /**
     * @return mixed
     */
    public function getCampaignType()
    {
        return $this->campaignType;
    }

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

}