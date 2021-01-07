<?php

namespace App\EventListener;

use App\Entity\TrickVideo;
use App\Service\Trick\Video\TrickVideoTagManager;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class TrickVideoListener
{
    /**
     * @var TrickVideoTagManager
     */
    private TrickVideoTagManager $tagManager;

    public function __construct(TrickVideoTagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    /**
     * @param TrickVideo         $video
     * @param LifecycleEventArgs $event
     */
    public function prePersist(TrickVideo $video, LifecycleEventArgs $event): void
    {
        $event = null;
        $this->tagManager->setTag($video);
    }

    /**
     * @param TrickVideo         $video
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(TrickVideo $video, LifecycleEventArgs $event): void
    {
        $event = null;
        $this->tagManager->setTag($video);
    }
}
