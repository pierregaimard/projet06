<?php

namespace App\EventListener;

use App\Entity\Trick;
use App\Service\Trick\Slug\TrickSlugManager;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Security;

class TrickListener
{
    /**
     * @var TrickSlugManager
     */
    private TrickSlugManager $slugManager;

    /**
     * @var Security
     */
    private Security $security;

    /**
     * @param TrickSlugManager $slugManager
     */
    public function __construct(TrickSlugManager $slugManager, Security $security)
    {
        $this->slugManager = $slugManager;
        $this->security    = $security;
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

        # Set trick author
        $trick->setAuthor($this->security->getUser());
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
