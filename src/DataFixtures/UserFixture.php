<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE = 'user';

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstName('Pierre');
        $user->setLastName('Gaimard');
        $user->setUsername('pgaimard');
        $user->setEmail('pierre@gaimard-family.fr');
        $user->setPlainPassword('pass');
        $user->setStatus($this->getReference(UserStatus::STATUS_ACTIVE));
        $user->setRoles([User::ROLE_USER]);

        $this->addReference(self::REFERENCE, $user);

        $manager->persist($user);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            UserStatusFixtures::class
        ];
    }
}
