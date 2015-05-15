<?php
/**
 * Created by PhpStorm.
 * User: fliak
 * Date: 11.5.15
 * Time: 23.07
 */

namespace Soil\DiscoverBundle\Entity\Schema;

class ImageObject extends Thing {

    /**
     * @var string
     *
     */
    public $thumbnail;

    /**
     * @var string
     */
    public $caption;

    public $contentUrl;

    /**
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * @param string $caption
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;
    }

    /**
     * @return mixed
     */
    public function getContentUrl()
    {
        return $this->contentUrl;
    }

    /**
     * @param mixed $contentUrl
     */
    public function setContentUrl($contentUrl)
    {
        $this->contentUrl = $contentUrl;
    }

    /**
     * @return string
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @param string $thumbnail
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }






} 