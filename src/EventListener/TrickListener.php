<?php

namespace App\EventListener;

use App\Entity\Trick;
use App\Service\Trick\Slug\TrickSlugManager;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class TrickListener
{
    /**
     * @var TrickSlugManager
     */
    private TrickSlugManager $slugManager;

    /**
     * @param TrickSlugManager $slugManager
     */
    public function __construct(TrickSlugManager $slugManager)
    {
        $this->slugManager = $slugManager;
    }

    /**
     * @param Trick              $trick
     * @param LifecycleEventArgs $event
     */
    public function prePersist(Trick $trick, LifecycleEventArgs $event): void
    {
        $event = null;
        # set trick slug
        $this->slugManager->setSlug($trick);
    }

    /**
     * @param Trick              $trick
     * @param LifecycleEventArgs $event
     */
    public function preUpdate(Trick $trick, LifecycleEventArgs $event): void
    {
        $event = null;
        # set trick slug
        $this->slugManager->setSlug($trick);
    }
}
