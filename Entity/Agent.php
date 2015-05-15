<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 11.42
 */


namespace Soil\DiscoverBundle\Entity;

use Soil\DiscoverBundle\Entity\Schema\ImageObject;

use Soil\DiscoverBundle\Annotation as RDF;


class Agent extends Generic {


    protected $mbox;

    /**
     * @var string
     *
     * @RDF\Match("foaf:firstName")
     *
     */
    protected $firstName;

    protected $lastName;


    /**
     * @var string
     *
     * @RDF\Match("foaf:name")
     *
     */
    protected $displayName;

    /**
     * @var ImageObject
     *
     * @RDF\Match("foaf:image")
     */
    protected $img;

    /**
     * @var
     *
     * @RDF\Match("foaf:locale")
     *
     */
    protected $locale;

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return ImageObject
     */
    public function getImg()
    {
        return $this->img;
    }

    /**
     * @param ImageObject $img
     */
    public function setImg($img)
    {
        $this->img = $img;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getMbox()
    {
        return $this->mbox;
    }


    public function getEmail()  {
        $mailURI = (string) $this->mbox;
        $colonPosition = strpos($mailURI, ':');

        if ($colonPosition === false) {
            return $mailURI;
        }
        else    {
            return $email = substr($mailURI, $colonPosition + 1);
        }
    }

    /**
     * @param mixed $mbox
     */
    public function setMbox($mbox)
    {
        $this->mbox = $mbox;
    }

    /**
     * @return mixed
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param mixed $locale
     */
    public function setLocale($locale)
    {
        $this->locale = $locale;
    }





} 