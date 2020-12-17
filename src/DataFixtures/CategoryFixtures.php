<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    private const KEY_OBJECT = 'object';
    private const KEY_NAME   = 'name';

    public const CATEGORY_GRABS    = 'grabs';
    public const CATEGORY_FLIPS    = 'flips';
    public const CATEGORY_INVERTED = 'inverted';
    public const CATEGORY_SLIDES   = 'slides';
    public const CATEGORY_STALLS   = 'stalls';
    public const CATEGORY_TWEAKS   = 'tweaks';

    public function load(ObjectManager $manager)
    {
        $data =  [
            [self::KEY_OBJECT => self::CATEGORY_GRABS, self::KEY_NAME => 'gRaBS'],
            [self::KEY_OBJECT => self::CATEGORY_FLIPS, self::KEY_NAME => 'fLIpS'],
            [self::KEY_OBJECT => self::CATEGORY_INVERTED, self::KEY_NAME => 'iNVerteD hAnD plANtS'],
            [self::KEY_OBJECT => self::CATEGORY_SLIDES, self::KEY_NAME => 'sLiDES'],
            [self::KEY_OBJECT => self::CATEGORY_STALLS, self::KEY_NAME => 'sTallS'],
            [self::KEY_OBJECT => self::CATEGORY_TWEAKS, self::KEY_NAME => 'tWeaKS'],
        ];

        foreach ($data as $item) {
            $name = $item[self::KEY_OBJECT];
            $$name = new Category();
            $$name->setName($item[self::KEY_NAME]);
            $manager->persist($$name);
            $this->addReference($item[self::KEY_OBJECT], $$name);
        }

        $manager->flush();
    }
}
