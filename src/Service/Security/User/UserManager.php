<?php

namespace App\Service\Security\User;

use App\Entity\User;
use App\Entity\UserStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;

    /**
     * @param EntityManagerInterface       $manager
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder)
    {
        $this->manager = $manager;
        $this->encoder = $encoder;
    }

    /**
     * Set User password hash & remove plainPassword
     *
     * @param User $user
     */
    public function setPassword(User $user)
    {
        $user->setPassword($this->encoder->encodePassword($user, $user->getPlainPassword()));
        $user->eraseCredentials();
    }

    /**
     * initialize User default data before creation.
     *
     * @param User $user
     */
    public function initialize(User $user)
    {
        $this->setPassword($user);
        $this->setStatus($user, UserStatus::STATUS_VALIDATION);
        $user->setRoles([User::ROLE_USER]);
    }

    /**
     * @param User $user
     */
    public function activate(User $user)
    {
        $this->setStatus($user, UserStatus::STATUS_ACTIVE);
        $this->manager->flush();
    }

    /**
     * @param User   $user
     * @param string $status
     */
    private function setStatus(User $user, string $status)
    {
        $statusRepository = $this->manager->getRepository(UserStatus::class);
        $user->setStatus($statusRepository->findOneBy(['status' => $status]));
    }
}
