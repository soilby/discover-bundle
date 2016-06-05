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

/**
 * Class Agent
 * @package Soil\DiscoverBundle\Entity
 *
 * @RDF\Vocab("foaf")
 * @RDF\Iri("tal:GenericTalakaAgent")
 */
class Agent extends Generic {

    protected $mbox;

    protected $environment;


    protected $phone;

    /**
     * @var string
     *
     * @RDF\Iri(value="foaf:firstName", persist=false)
     *
     */
    protected $firstName;

    /**
     * @var string
     *
     * @RDF\Iri(value="foaf:lastName", persist=false)
     *
     */
    protected $lastName;


    /**
     * @var string
     *
     * @RDF\Iri(value="foaf:name", persist=false)
     *
     */
    protected $displayName;

    /**
     * @var ImageObject
     *
     * @RDF\Iri(value="foaf:image", persist=false)
     */
    protected $img;

    /**
     * @var
     *
     * @RDF\Iri(value="foaf:locale", persist=false)
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

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    public function getPhoneNumber()    {
        $phoneURI = (string) $this->phone;
        $colonPosition = strpos($phoneURI, ':');

        if ($colonPosition === false) {
            return $phoneURI;
        }
        else    {
            return substr($phoneURI, $colonPosition + 1);
        }
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return mixed
     */
    public function getEnvironment()
    {
        if ($this->environment) {
            return $this->environment;
        }

        $uri = $this->getUri();
        if (
            strpos($uri, 'http://talaka.by') === 0 ||
            strpos($uri, 'http://www.talaka.by') === 0
        ) {
            return 'production';
        }

        return 'development';

    }

    /**
     * @param mixed $environment
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
    }






} 