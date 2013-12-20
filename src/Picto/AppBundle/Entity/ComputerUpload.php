<?php

namespace Picto\AppBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * ComputerUpload
 */
class ComputerUpload
{

    /**
     * @var Symfony\Component\HttpFoundation\File\UploadedFile
     */
    private $image;

    /**
     * @const string
     */
    const SOURCE = 'computer';

    /**
     * Set image
     *
     * @param \Symfony\Component\HttpFoundation\File\UploadedFile $image
     * @return ComputerUpload
     */
    public function setImage(UploadedFile $image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \Symfony\Component\HttpFoundation\File\UploadedFile
     */
    public function getImage()
    {
        return $this->image;
    }

}
