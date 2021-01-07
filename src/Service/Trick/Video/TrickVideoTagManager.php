<?php

namespace App\Service\Trick\Video;

use App\Entity\TrickVideo;

class TrickVideoTagManager
{
    /**
     * @param TrickVideo $video
     */
    public function setTag(TrickVideo $video)
    {
        $video->setTag($this->getSrc($video->getTag()));
    }

    /**
     * @param $tag
     *
     * @return string|false
     */
    private function getSrc($tag)
    {
        foreach (explode(' ', $tag) as $item) {
            if (substr($item, 0, 4) === 'src=') {
                return substr($item, 5, -1);
            }
        }

        return false;
    }

    /**
     * @param $tag
     *
     * @return bool
     */
    public function isValid($tag): bool
    {
        return $this->getSrc($tag) !== false;
    }
}
