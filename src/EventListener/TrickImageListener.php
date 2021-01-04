<?php

namespace App\EventListener;

use App\Entity\TrickImage;
use App\Service\Trick\Image\TrickImageManager;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class TrickImageListener
{
    /**
     * @var TrickImageManager
     */
    private TrickImageManager $imageManager;

    /**
     * @param TrickImageManager $imageManager
     */
    public function __construct(TrickImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    /**
     * @param TrickImage         $image
     * @param LifecycleEventArgs $event
     */
    public function postPersist(TrickImage $image, LifecycleEventArgs $event): void
    {
        # Move, rename and format image
        # Set image fileName.
        $this->imageManager->setFinalImage($image);
        $event->getObjectManager()->flush();
    }

    /**
     * @param TrickImage         $image
     * @param LifecycleEventArgs $event
     */
    public function postRemove(TrickImage $image, LifecycleEventArgs $event): void
    {
        $event = null;
        # Remove image
        $this->imageManager->removeImage($image);
    }
}
