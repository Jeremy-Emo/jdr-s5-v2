<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @MappedSuperclass
 * @ORM\HasLifecycleCallbacks()
 */
class UploadImageEntity
{
    private $temp;

    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $image;

    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
        if (isset($this->image)) {
            $this->temp = $this->image;
            $this->image = null;
        } else {
            $this->image = 'initial';
        }
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->getFile()) {
            $filename = sha1(uniqid(mt_rand(), true));
            $this->image = '/img/uploads/' . $filename . '.' . $this->getFile()->guessExtension();
        }
    }

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {
        if (null === $this->getFile()) {
            return;
        }
        $this->getFile()->move($this->getUploadRootDir(), $this->image);
        if (isset($this->temp)) {
            unlink($this->getUploadRootDir() . '/' . $this->temp);
            $this->temp = null;
        }
        $this->file = null;
    }

    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        $file = $this->getAbsolutePath();
        if ($file && strpos($this->image, '.')) {
            unlink($file);
        }
    }

    public function getAbsolutePath()
    {
        return null === $this->image
            ? null
            : '/img/' . $this->image;
    }

    public function getWebPath()
    {
        return !empty($this->image) ? $this->image : null;
    }

    protected function getUploadRootDir()
    {
        return $_ENV['ABSOLUTE_PATH_FOR_UPLOAD'] . '/img/uploads/';
    }
}