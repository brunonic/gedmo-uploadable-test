<?php

namespace AppBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="image")
 *
 * @Gedmo\Uploadable(allowOverwrite=true, pathMethod="getUploadableDirPath", filenameGenerator="SHA1")
 */
class Image
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     *
     * @Gedmo\UploadableFilePath
     */
    private $path;

    /**
     * @var UploadedFile
     */
    protected $file;

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
     * @return string
     */
    public function getUploadableDirPath()
    {
        return 'uploads/image';
    }

    /**
     * Set path
     *
     * @param string $path
     * @param bool   $force
     *
     * @return ProductImage
     */
    public function setPath($path, $force = false)
    {
        if ($this->getFile() or $force) {
            $this->path = $path;
        }

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     *
     * @return ProductImage
     */
    public function setFile($file)
    {
        $this->file = $file;
    }
}
