<?php

namespace Picto\AppBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * UrlUpload
 */
class UrlUpload
{

    /**
     * @var string
     */
    private $url;

    /**
     * @const string
     */
    const SOURCE = 'url';

    /**
     * Set url
     *
     * @param string $url
     * @return Upload
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Get basename
     *
     * @return string
     */
    public function getBasename()
    {
        $urlBits = parse_url($this->getUrl());
        $basename = basename($this->getUrl());

        // If the URL has a query string, remove it.
        if (isset($urlBits['query'])) {
            $basename = str_replace($urlBits['query'], '', $basename);
            $basename = rtrim($basename, '?');
        }

        return $basename;
    }
}
