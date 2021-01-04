<?php

namespace App\Service\Trick\Slug;

use App\Entity\Trick;

class TrickSlugManager
{
    /**
     * @param Trick $trick
     */
    public function setSlug(Trick $trick): void
    {
        $trick->setSlug($this->getSlug($trick->getName()));
    }

    /**
     * @param string $trickName
     *
     * @return string
     */
    private function getSlug(string $trickName): string
    {
        return str_replace(' ', '-', strtolower($trickName));
    }
}
