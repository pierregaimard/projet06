<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public const CATEGORY_GRABS    = 'grabs';
    public const CATEGORY_FLIPS    = 'flips';
    public const CATEGORY_INVERTED = 'inverted';
    public const CATEGORY_SLIDES   = 'slides';
    public const CATEGORY_STALLS   = 'stalls';
    public const CATEGORY_TWEAKS   = 'tweaks';

    public function load(ObjectManager $manager)
    {
        // Grabs
        $grabs = new Category();
        $grabs->setName('gRaBS');
        $manager->persist($grabs);

        // Flips
        $flips = new Category();
        $flips->setName('fLIpS');
        $manager->persist($flips);

        // Inverted hand plants
        $inverted = new Category();
        $inverted->setName('iNVerteD hAnD plANtS');
        $manager->persist($inverted);

        // Slides
        $slides = new Category();
        $slides->setName('sLiDES');
        $manager->persist($slides);

        // Stalls
        $stalls = new Category();
        $stalls->setName('sTallS');
        $manager->persist($stalls);

        // Tweaks
        $tweaks = new Category();
        $tweaks->setName('tWeaKS');
        $manager->persist($tweaks);

        $manager->flush();

        $this->addReference(self::CATEGORY_GRABS, $grabs);
        $this->addReference(self::CATEGORY_FLIPS, $flips);
        $this->addReference(self::CATEGORY_INVERTED, $inverted);
        $this->addReference(self::CATEGORY_SLIDES, $slides);
        $this->addReference(self::CATEGORY_STALLS, $stalls);
        $this->addReference(self::CATEGORY_TWEAKS, $tweaks);
    }
}
