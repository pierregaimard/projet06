<?php

namespace App\DataFixtures;

use App\Entity\UserStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserStatusFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // Validation status
        $validation = new UserStatus();
        $validation->setStatus(UserStatus::STATUS_VALIDATION);
        $manager->persist($validation);

        // Active status
        $active = new UserStatus();
        $active->setStatus(UserStatus::STATUS_ACTIVE);
        $manager->persist($active);

        $manager->flush();
    }
}
