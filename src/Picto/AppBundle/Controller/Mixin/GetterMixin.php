<?php

namespace Picto\AppBundle\Controller\Mixin;

use Picto\AppBundle\Entity\Image;

trait GetterMixin
{

    public function getImage($hash)
    {
        $image = $this->getEntityManager()
            ->getRepository('PictoAppBundle:Image')
            ->findOneByHash($hash);

        return $image;
    }

    public function getNotFoundImage()
    {
        $image = new Image;
        $image->setHash($this->container->getParameter('image_not_found_hash'))
            ->setFilePath($this->container->getParameter('image_not_found_path'))
            ->setFileName(basename($image->getFilePath()))
            ->setFileExtension($this->container->getParameter('image_not_found_extension'))
            ->setRawHash(sha1_file($image->getFilePath()))
            ->setFileSize(filesize($image->getFilePath()))
            ->setContentType($this->container->getParameter('image_not_found_mime_type'));

        return $image;
    }

    public function getEntityManager()
    {
        return $this->get('doctrine')
            ->getManager();
    }

}
