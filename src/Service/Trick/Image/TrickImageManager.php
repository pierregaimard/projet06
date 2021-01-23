<?php

namespace App\Service\Trick\Image;

use App\Entity\TrickImage;
use Symfony\Component\Filesystem\Filesystem;

class TrickImageManager
{
    /**
     * @var string
     */
    private string $imgServerDir;

    /**
     * @var int
     */
    private int $width;

    /**
     * @var int
     */
    private int $resolution;

    /**
     * @var TrickImageFormater
     */
    private TrickImageFormater $imageFormater;

    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;

    /**
     * @param string             $imgServerDir
     * @param int                $width
     * @param int                $resolution
     * @param TrickImageFormater $imageFormater
     * @param Filesystem         $filesystem
     */
    public function __construct(
        string $imgServerDir,
        int $width,
        int $resolution,
        TrickImageFormater $imageFormater,
        Filesystem $filesystem
    ) {
        $this->imgServerDir  = $imgServerDir;
        $this->imageFormater = $imageFormater;
        $this->width         = $width;
        $this->resolution    = $resolution;
        $this->filesystem    = $filesystem;
    }

    /**
     * @param TrickImage $image
     */
    public function setFinalImage(TrickImage $image): void
    {
        if ($image->getUploadedFile() === null) {
            return;
        }

        $name = $this->getFileName($image);

        # Set TrickImage file name
        $image->setFileName($name);

        # Move TrickImage file into final directory.
        $this->moveFile($image);

        # Format the image (with and resolution)
        $this->imageFormater->formatImage($this->imgServerDir . $name, $this->width, $this->resolution);
    }

    /**
     * @param TrickImage $image
     */
    public function removeImage(TrickImage $image): void
    {
        $this->filesystem->remove($this->imgServerDir . $image->getFileName());
    }

    /**
     * The file name is the id of the entity with file extension.
     *
     * @param TrickImage $image
     *
     * @return string
     */
    private function getFileName(TrickImage $image): string
    {
        $category = str_replace(' ', '-', strtolower($image->getTrick()->getCategory()->getName()));

        return $category . '-' . $image->getTrick()->getSlug() . '-' . $image->getId() . '.jpeg';
    }

    /**
     * Moves the uploaded image into final directory.
     *
     * @param TrickImage $image
     */
    private function moveFile(TrickImage $image): void
    {
        $image->getUploadedFile()->move($this->imgServerDir, $this->getFileName($image));
    }
}
