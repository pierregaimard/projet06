<?php

namespace App\DataFixtures;

use App\Entity\TrickImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class TrickImageFixtures extends Fixture implements DependentFixtureInterface
{
    private const KEY_COUNT = 'count';
    private const KEY_FILE  = 'file';
    private const KEY_TRICK = 'trick';

    public function load(ObjectManager $manager)
    {
        $data = [
            [
                self::KEY_COUNT => 5,
                self::KEY_FILE => 'grabs-mute-',
                self::KEY_TRICK => TrickFixtures::GRABS_MUTE
            ],
            [
                self::KEY_COUNT => 6,
                self::KEY_FILE => 'grabs-bloody-dracula-',
                self::KEY_TRICK => TrickFixtures::GRABS_DRACULA
            ],
            [
                self::KEY_COUNT => 4,
                self::KEY_FILE => 'flips-cork-',
                self::KEY_TRICK => TrickFixtures::FLIPS_CORK
            ],
            [
                self::KEY_COUNT => 6,
                self::KEY_FILE => 'flips-layout-backflip-',
                self::KEY_TRICK => TrickFixtures::FLIPS_BACKFLIP
            ],
            [
                self::KEY_COUNT => 7,
                self::KEY_FILE => 'inverted-hand-plants-t-jear-',
                self::KEY_TRICK => TrickFixtures::INVERTED_JTEAR
            ],
            [
                self::KEY_COUNT => 5,
                self::KEY_FILE => 'slides-boardslide-',
                self::KEY_TRICK => TrickFixtures::SLIDES_BOARDSLIDE
            ],
            [
                self::KEY_COUNT => 2,
                self::KEY_FILE => 'slides-the-gutterball-',
                self::KEY_TRICK => TrickFixtures::SLIDES_GUTTERBALL
            ],
            [
                self::KEY_COUNT => 4,
                self::KEY_FILE => 'stalls-nose-pick-',
                self::KEY_TRICK => TrickFixtures::STALLS_NOSEPICK
            ],
            [
                self::KEY_COUNT => 4,
                self::KEY_FILE => 'tweaks-one-footed-',
                self::KEY_TRICK => TrickFixtures::TWEAKS_FOOTED
            ],
            [
                self::KEY_COUNT => 4,
                self::KEY_FILE => 'tweaks-tweak-',
                self::KEY_TRICK => TrickFixtures::TWEAKS_TWEAK
            ],
        ];

        $count = 0;

        foreach ($data as $item) {
            for ($subCount = 1; $subCount <= $item[self::KEY_COUNT]; $subCount++) {
                $count++;
                $name = 'trickImage' . $count;
                $$name = new TrickImage();
                $$name->setTrick($this->getReference($item[self::KEY_TRICK]));
                $$name->setFileName($item[self::KEY_FILE] . $count . '.jpg');
                $manager->persist($$name);
            }
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            TrickFixtures::class
        ];
    }
}
