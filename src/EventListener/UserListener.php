<?php

namespace App\EventListener;

use App\Entity\User;
use App\Service\Security\User\UserManager;
use Doctrine\Persistence\Event\LifecycleEventArgs;

class UserListener
{
    /**
     * @var UserManager
     */
    private UserManager $userManager;

    /**
     * UserListener constructor.
     *
     * @param UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->userManager = $userManager;
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
}
