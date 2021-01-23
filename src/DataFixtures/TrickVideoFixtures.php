<?php

namespace App\DataFixtures;

use App\Entity\TrickVideo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Twig\Environment;

class TrickVideoFixtures extends Fixture implements DependentFixtureInterface
{
    private const TRICK_REF = 'trickRef';
    private const TAGS = 'tag';

    private Environment $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    public function load(ObjectManager $manager)
    {
        $data = [
            [
                self::TRICK_REF => TrickFixtures::GRABS_MUTE,
                self::TAGS => ['Opg5g4zsiGY', 'jm19nEvmZgM', 'fIhwQc93iHo', '_hxLS2ErMiY']
            ],
            [
                self::TRICK_REF => TrickFixtures::GRABS_DRACULA,
                self::TAGS => ['UU9iKINvlyU', 'v7AmF8c4ZLw']
            ],
            [
                self::TRICK_REF => TrickFixtures::FLIPS_CORK,
                self::TAGS => ['FMHiSF0rHF8', 'euDhH_5hP0w', 'ExgTN26g2KQ', 'P5ZI-d-eHsI']
            ],
            [
                self::TRICK_REF => TrickFixtures::FLIPS_BACKFLIP,
                self::TAGS => ['0sehBOkD01Q', 'g0L0LnF3JiY', 'AMsWP9WJS_0', 'vf9Z05XY79A']
            ],
            [
                self::TRICK_REF => TrickFixtures::INVERTED_JTEAR,
                self::TAGS => ['us8tZcQ1GrY', '2iS21nuz03Y', 'UgYiQFmLm6s']
            ],
            [
                self::TRICK_REF => TrickFixtures::SLIDES_BOARDSLIDE,
                self::TAGS => ['HVx9iVeIP2Q', 'N-HT7FhtXjs', 'gO5GLk7oQhU', 'Dafmcn0UR5g', 'lnbUwCPbbQM']
            ],
            [
                self::TRICK_REF => TrickFixtures::SLIDES_GUTTERBALL,
                self::TAGS => ['87KxS5B014o', 'Zc8Gu8FwZkQ', 'Ey5elKTrUCk']
            ],
            [
                self::TRICK_REF => TrickFixtures::STALLS_NOSEPICK,
                self::TAGS => ['czpV-FOBHY4', 'gZFWW4Vus-Q', 's3jRiFyOijw', 'RGzktM_J6WQ', '5q2ivIXJx3U']
            ],
            [
                self::TRICK_REF => TrickFixtures::TWEAKS_FOOTED,
                self::TAGS => ['LWUfrwCofuA', '4IVdWdvsrVA', 'K-RKP3BizWM', 'LUyoIf2pXLA']
            ],
            [
                self::TRICK_REF => TrickFixtures::TWEAKS_TWEAK,
                self::TAGS => ['VX7e1LTuRqE', 'O3VphBHy7B4', 'N2335teLIBY', 'Zc8Gu8FwZkQ', 'FXGxvgid-X0']
            ],
        ];

        $count = 0;

        foreach ($data as $item) {
            foreach ($item[self::TAGS] as $tag) {
                $count++;
                $name = 'TrickVideo' . $count;
                $$name = new TrickVideo();
                $$name->setTrick($this->getReference($item[self::TRICK_REF]));
                $$name->setTag($this->environment->render(
                    'data_fixtures/_iframe.html.twig',
                    [
                        'videoId' => $tag
                    ]
                ));
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
