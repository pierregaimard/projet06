<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\Account\AccountImageManager;
use App\Service\Security\User\UserManager;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UserListener
{
    /**
     * @var UserManager
     */
    private UserManager $userManager;

    /**
     * @var AccountImageManager
     */
    private AccountImageManager $imageManager;

    /**
     * UserListener constructor.
     *
     * @param UserManager         $userManager
     * @param AccountImageManager $imageManager
     */
    public function __construct(UserManager $userManager, AccountImageManager $imageManager)
    {
        $this->userManager = $userManager;
        $this->imageManager = $imageManager;
    }

    /**
     * @param User               $user
     * @param LifecycleEventArgs $event
     */
    public function prePersist(User $user, LifecycleEventArgs $event)
    {
        unset($event);
        $this->userManager->initialize($user);
    }

    /**
     * @param User               $user
     * @param LifecycleEventArgs $event
     */
    public function postRemove(User $user, LifecycleEventArgs $event)
    {
        unset($event);
        $this->imageManager->remove($user);
    }
}
