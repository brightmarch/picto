<?php

namespace Picto\AppBundle\Tests\Library\Job;

use Picto\AppBundle\Entity\Image;
use Picto\AppBundle\Library\Job\UploadJob;
use Picto\AppBundle\Tests\TestCase;

class UploadJobTest extends TestCase
{

    public function testUploadingImageRequiresImageToExist()
    {
        $args = $this->generateJobArgs(0);

        $job = new UploadJob;
        $job->args = $args;

        $this->assertTrue($job->run($args));
    }

    public function testUploadingImage()
    {
        $image = $this->getEntityManager()
            ->getRepository('PictoAppBundle:Image')
            ->findOneByHash($this->getContainer()->getParameter('picto_test_image_hash'));

        $this->assertNull($image->getRemoteUrl());

        $args = $this->generateJobArgs($image->getId());

        $job = new UploadJob;
        $job->args = $args;
        $job->run($args);

        $this->getEntityManager()->refresh($image);

        $this->assertNotNull($image->getRemoteUrl());
    }

    private function generateJobArgs($imageId)
    {
        $kernelRootDir = $this->getContainer()
            ->getParameter('kernel.root_dir');

        $args = [
            'imageId' => (int)$imageId,
            'kernel.root_dir' => $kernelRootDir,
            'kernel.environment' => 'test'
        ];

        return $args;
    }

}
