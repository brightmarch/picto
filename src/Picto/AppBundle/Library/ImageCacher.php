<?php

namespace Picto\AppBundle\Library;

use Picto\AppBundle\Entity\Image;

class ImageCacher
{

    /** @const Redis */
    private $cache;

    /** @const integer */
    const CACHE_EXPIRATION = 300;

    /** @const integer */
    const CACHE_BUMP = 5;

    public function __construct($cache)
    {
        $this->cache = $cache;
    }

    /**
     * Caches an image. If the image already exists,
     * the TTL is extended by several seconds. Otherwise,
     * the image is freshly cached.
     *
     * @param string $hash
     * @param Picto\AppBundle\Entity\Image $image
     * @return boolean
     */
    public function cache($hash, Image $image)
    {
        $isCached = false;

        if ($this->exists($hash)) {
            // The image already exists in the cache, just increase its
            // TTL by a few seconds because it is a popular image.
            $ttl = ((int)$this->cache->ttl($hash) + self::CACHE_BUMP);
            $this->cache->expire($hash, $ttl);

            $isCached = true;
        } else {
            // Need to determine where to get the actual raw image from.
            $imagePath = null;

            if (is_file($image->getFilePath())) {
                // The image exists on the local disk, just
                // grab it from there (much faster).
                $imagePath = $image->getFilePath();
            } else {
                // The image has been purged from the local disk,
                // so grab it from S3.
                $imagePath = $image->getRemoteUrl();
            }

            if ($imagePath) {
                // Image is not in the cache, so cache it.
                $imageContents = file_get_contents($imagePath);

                if (false !== $imageContents) {
                    $imageHash = base64_encode($imageContents);

                    $this->cache->set($hash, $imageHash);
                    $this->cache->expire($hash, self::CACHE_EXPIRATION);

                    $isCached = true;
                }
            }
        }

        return $isCached;
    }

    /**
     * Gets an image from the cache.
     *
     * @param string $hash
     * @return mixed
     */
    public function get($hash)
    {
        if ($this->exists($hash)) {
            return base64_decode($this->cache->get($hash));
        }

        return null;
    }

    /**
     * Removes a hash from the cache.
     *
     * @param string $hash
     * @return boolean
     */
    public function remove($hash)
    {
        return $this->cache->del($hash);

        return true;
    }

    /**
     * Tests to see if a hash exists.
     *
     * @param string $hash
     * @return boolean
     */
    public function exists($hash)
    {
        return $this->cache->exists($hash);
    }

}
