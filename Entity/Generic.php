<?php
namespace Soil\DiscoverBundle\Entity;
use EasyRdf\Literal;
use EasyRdf\Resource;
use Soil\DiscoverBundle\Annotation as RDF;

/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 17.2.15
 * Time: 11.44
 *
 * @RDF\Vocab("tal")
 * @RDF\Iri("tal:GenericTalakaEntity")
 */
class Generic {

    protected $origin;

    protected $values = [];

    protected $rdfNamespace;

    protected $uri;

    /**
     * Add access by origin names
     *
     * @param $name
     * @return mixed
     */
    public function __get($name)    {
        if (array_key_exists($name, $this->values)) {
            return $this->values[$name];
        }

        return null;
    }

    public function __set($name, $value)    {
        $this->values[$name] = $value;
    }

    public function __isset($name) {
        return array_key_exists($name, $this->values);
    }

    /**
     * @return mixed
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param mixed $origin
     */
    public function setOrigin($origin)
    {
        $this->origin = $origin;
    }

    public function getValues() {
        return $this->values;
    }

    /**
     * @return mixed
     */
    public function getRdfNamespace()
    {
        return $this->rdfNamespace;
    }

    /**
     * @param mixed $rdfNamespace
     */
    public function setRdfNamespace($rdfNamespace)
    {
        $this->rdfNamespace = $rdfNamespace;
    }

    /**
     * @return mixed
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param mixed $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }

    
    

} 