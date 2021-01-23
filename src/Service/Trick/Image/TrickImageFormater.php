<?php

namespace App\Service\Trick\Image;

class TrickImageFormater
{
    /**
     * Set image width and resolution.
     *
     * Note: width and resolution are set in services.yaml
     *
     * @param string $file
     * @param int    $width
     * @param int    $resolution
     */
    public function formatImage(string $file, int $width, int $resolution)
    {
        $image = imagecreatefromjpeg($file);
        $image = $this->scaleImage($image, $width);
        $this->resizeImage($image, $resolution);
        imagejpeg($image, $file);
        imagedestroy($image);
    }

    private function scaleImage($ressource, int $width)
    {
        return imagescale($ressource, $width);
    }

    private function resizeImage($ressource, int $resolution)
    {
        imageresolution($ressource, $resolution);
    }
}
