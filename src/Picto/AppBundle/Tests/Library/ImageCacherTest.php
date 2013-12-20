<?php

namespace Picto\AppBundle\Tests\Library;

use Picto\AppBundle\Entity\Image;
use Picto\AppBundle\Tests\TestCase;

class ImageCacherTest extends TestCase
{

    public function testGettingInvalidImage()
    {
        $imageCacher = $this->getImageCacher();

        $this->assertNull($imageCacher->get('invalid-hash'));
    }

    public function testCachingImageRequiresValidImagePath()
    {
        $imageCacher = $this->getImageCacher();

        $image = new Image;

        $this->assertFalse($imageCacher->cache($image->getHash(), $image));
    }

    public function testCachingImageFromDisk()
    {
        $hash = $this->getContainer()
            ->getParameter('picto_test_image_hash');

        $imageCacher = $this->getImageCacher();

        $image = new Image;
        $image->setHash($hash)
            ->setFilePath($this->getContainer()->getParameter('picto_test_image_path'));

        $this->assertTrue($imageCacher->cache($hash, $image));
    }

    private function getImageCacher()
    {
        return $this->getContainer()
            ->get('picto_app.image_cacher');
    }

}
