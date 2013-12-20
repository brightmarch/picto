<?php

namespace Picto\AppBundle\Entity;

use Picto\AppBundle\Library\Constants;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\File\UploadedFile;

use \DateTime;

/**
 * Image
 */
class Image
{

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var integer
     */
    private $status = Constants::DISABLED;

    /**
     * @var string
     */
    private $rawHash;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $fileSize;

    /**
     * @var string
     */
    private $fileExtension;

    /**
     * @var integer
     */
    private $width = 0;

    /**
     * @var integer
     */
    private $height = 0;

    /**
     * @var string
     */
    private $contentType;

    /**
     * @var integer
     */
    private $uploadTime = 0;

    /**
     * @var float
     */
    private $memoryUsage = 0.0;

    /**
     * @var string
     */
    private $remoteUrl;

    /**
     * @var integer
     */
    private $viewCount = 0;

    /**
     * @var string
     */
    private $filePath;

    /**
     * @var string
     */
    private $jobId;

    /**
     * @var blob
     */
    private $image;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Image
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Image
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return Image
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set rawHash
     *
     * @param string $rawHash
     * @return Image
     */
    public function setRawHash($rawHash)
    {
        $this->rawHash = $rawHash;

        return $this;
    }

    /**
     * Get rawHash
     *
     * @return string 
     */
    public function getRawHash()
    {
        return $this->rawHash;
    }

    /**
     * Set hash
     *
     * @param string $hash
     * @return Image
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string 
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     * @return Image
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string 
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set fileSize
     *
     * @param string $fileSize
     * @return Image
     */
    public function setFileSize($fileSize)
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    /**
     * Get fileSize
     *
     * @return string 
     */
    public function getFileSize()
    {
        return $this->fileSize;
    }

    /**
     * Set fileExtension
     *
     * @param string $fileExtension
     * @return Image
     */
    public function setFileExtension($fileExtension)
    {
        $this->fileExtension = strtolower($fileExtension);

        return $this;
    }

    /**
     * Get fileExtension
     *
     * @return string 
     */
    public function getFileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * Set width
     *
     * @param integer $width
     * @return Image
     */
    public function setWidth($width)
    {
        $this->width = (int)$width;

        return $this;
    }

    /**
     * Get width
     *
     * @return integer 
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param integer $height
     * @return Image
     */
    public function setHeight($height)
    {
        $this->height = (int)$height;

        return $this;
    }

    /**
     * Get height
     *
     * @return integer 
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set contentType
     *
     * @param string $contentType
     * @return Image
     */
    public function setContentType($contentType)
    {
        $this->contentType = strtolower($contentType);

        return $this;
    }

    /**
     * Get contentType
     *
     * @return string 
     */
    public function getContentType()
    {
        return $this->contentType;
    }

    /**
     * Set uploadTime
     *
     * @param integer $uploadTime
     * @return Image
     */
    public function setUploadTime($uploadTime)
    {
        $this->uploadTime = (int)$uploadTime;

        return $this;
    }

    /**
     * Get uploadTime
     *
     * @return integer 
     */
    public function getUploadTime()
    {
        return $this->uploadTime;
    }

    /**
     * Set memoryUsage
     *
     * @param float $memoryUsage
     * @return Image
     */
    public function setMemoryUsage($memoryUsage)
    {
        $this->memoryUsage = (float)$memoryUsage;

        return $this;
    }

    /**
     * Get memoryUsage
     *
     * @return float 
     */
    public function getMemoryUsage()
    {
        return $this->memoryUsage;
    }

    /**
     * Set remoteUrl
     *
     * @param string $remoteUrl
     * @return Image
     */
    public function setRemoteUrl($remoteUrl)
    {
        $this->remoteUrl = $remoteUrl;

        return $this;
    }

    /**
     * Get remoteUrl
     *
     * @return string 
     */
    public function getRemoteUrl()
    {
        return $this->remoteUrl;
    }

    /**
     * Set viewCount
     *
     * @param integer $viewCount
     * @return Image
     */
    public function setViewCount($viewCount)
    {
        $this->viewCount = (int)$viewCount;

        return $this;
    }

    /**
     * Get viewCount
     *
     * @return integer
     */
    public function getViewCount()
    {
        return $this->viewCount;
    }

    /**
     * Set filePath
     *
     * @param string $filePath
     * @return Image
     */
    public function setFilePath($filePath)
    {
        $this->filePath = $filePath;

        return $this;
    }

    /**
     * Get filePath
     *
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }

    /**
     * Set jobId
     *
     * @param string $jobId
     * @return Image
     */
    public function setJobId($jobId)
    {
        $this->jobId = $jobId;

        return $this;
    }

    /**
     * Get jobId
     *
     * @return string
     */
    public function getJobId()
    {
        return $this->jobId;
    }

    /**
     * @ORM\PrePersist
     */
    public function onCreate()
    {
        $this->setCreatedAt(new DateTime)
            ->setUpdatedAt(new DateTime)
            ->setStatus(Constants::ENABLED);
    }

    /**
     * @ORM\PreUpdate
     */
    public function onUpdate()
    {
        $this->setUpdatedAt(new DateTime);
    }

    /**
     * Get remoteFileName
     *
     * @return string
     */
    public function getRemoteFileName()
    {
        $remoteFileName = sprintf('%s.%s', $this->getHash(), $this->getFileExtension());

        return $remoteFileName;
    }

    /**
     * Get fileSize in KB.
     *
     * @return string
     */
    public function getFileSizeInKb()
    {
        $fileSize = round(($this->getFileSize() / 1024), 2);
        $fileSize = sprintf('%01.2fKB', $fileSize);

        return $fileSize;
    }

    /**
     * Is enabled
     *
     * @return boolean
     */
    public function isEnabled()
    {
        return ($this->getStatus() == Constants::ENABLED);
    }

    /**
     * Increment viewCount
     *
     * @return integer
     */
    public function incrementViewCount()
    {
        $this->setViewCount($this->getViewCount() + 1);

        return $this->getViewCount();
    }

}
