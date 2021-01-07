<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Twig\Environment;

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    private const KEY_OBJECT   = 'object';
    private const KEY_NAME     = 'name';
    private const KEY_SLUG     = 'slug';
    private const KEY_CATEGORY = 'category';

    public const GRABS_MUTE        = 'mute';
    public const GRABS_DRACULA     = 'bloodyDracula';
    public const FLIPS_CORK        = 'cork';
    public const FLIPS_BACKFLIP    = 'layoutBackflip';
    public const INVERTED_JTEAR    = 'jTear';
    public const SLIDES_BOARDSLIDE = 'boardslide';
    public const SLIDES_GUTTERBALL = 'gutterball';
    public const STALLS_NOSEPICK   = 'nosePick';
    public const TWEAKS_FOOTED     = 'oneFooted';
    public const TWEAKS_TWEAK      = 'tweak';

    private Environment $environment;

    public function __construct(Environment $environment)
    {
        $this->environment = $environment;
    }

    public function load(ObjectManager $manager)
    {
        $data =  [
            [
                self::KEY_OBJECT => self::GRABS_MUTE,
                self::KEY_NAME => 'Mute',
                self::KEY_SLUG => 'mute',
                self::KEY_CATEGORY => CategoryFixtures::CATEGORY_GRABS
            ],
            [
                self::KEY_OBJECT => self::GRABS_DRACULA,
                self::KEY_NAME => 'Bloody Dracula',
                self::KEY_SLUG => 'bloody-dracula',
                self::KEY_CATEGORY => CategoryFixtures::CATEGORY_GRABS
            ],
            [
                self::KEY_OBJECT => self::FLIPS_CORK,
                self::KEY_NAME => 'Cork',
                self::KEY_SLUG => 'cork',
                self::KEY_CATEGORY => CategoryFixtures::CATEGORY_FLIPS
            ],
            [
                self::KEY_OBJECT => self::FLIPS_BACKFLIP,
                self::KEY_NAME => 'Layout Backflip',
                self::KEY_SLUG => 'layout-backflip',
                self::KEY_CATEGORY => CategoryFixtures::CATEGORY_FLIPS
            ],
            [
                self::KEY_OBJECT => self::INVERTED_JTEAR,
                self::KEY_NAME => 'J-Tear',
                self::KEY_SLUG => 'j-tear',
                self::KEY_CATEGORY => CategoryFixtures::CATEGORY_INVERTED
            ],
            [
                self::KEY_OBJECT => self::SLIDES_BOARDSLIDE,
                self::KEY_NAME => 'Boardslide',
                self::KEY_SLUG => 'boardslide',
                self::KEY_CATEGORY => CategoryFixtures::CATEGORY_SLIDES
            ],
            [
                self::KEY_OBJECT => self::SLIDES_GUTTERBALL,
                self::KEY_NAME => 'Gutterball',
                self::KEY_SLUG => 'gutterball',
                self::KEY_CATEGORY => CategoryFixtures::CATEGORY_SLIDES
            ],
            [
                self::KEY_OBJECT => self::STALLS_NOSEPICK,
                self::KEY_NAME => 'Nose-pick',
                self::KEY_SLUG => 'nose-pick',
                self::KEY_CATEGORY => CategoryFixtures::CATEGORY_STALLS
            ],
            [
                self::KEY_OBJECT => self::TWEAKS_FOOTED,
                self::KEY_NAME => 'One-footed',
                self::KEY_SLUG => 'one-footed',
                self::KEY_CATEGORY => CategoryFixtures::CATEGORY_TWEAKS
            ],
            [
                self::KEY_OBJECT => self::TWEAKS_TWEAK,
                self::KEY_NAME => 'Tweak',
                self::KEY_SLUG => 'tweak',
                self::KEY_CATEGORY => CategoryFixtures::CATEGORY_TWEAKS
            ]
        ];

        foreach ($data as $item) {
            $name = $item[self::KEY_OBJECT];
            $$name = new Trick();
            $$name->setName($item[self::KEY_NAME]);
            $$name->setSlug($item[self::KEY_SLUG]);
            $$name->setDescription($this->environment->render(
                'data_fixtures/tricks_description.html.twig',
                [
                    'trick' => $item[self::KEY_OBJECT]
                ]
            ));
            $$name->setCategory($this->getReference($item[self::KEY_CATEGORY]));
            $$name->setAuthor($this->getReference(UserFixture::REFERENCE));
            $manager->persist($$name);
            $this->addReference($item[self::KEY_OBJECT], $$name);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixture::class,
        ];
    }
}
