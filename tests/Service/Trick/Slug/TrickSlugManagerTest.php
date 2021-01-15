<?php

namespace App\Tests\Service\Trick\Slug;

use App\Entity\Trick;
use App\Service\Trick\Slug\TrickSlugManager;
use PHPUnit\Framework\TestCase;

class TrickSlugManagerTest extends TestCase
{
    /**
     * @dataProvider nameProvider
     */
    public function testSetSlug($base, $expected)
    {
        $trick = new Trick();
        $trick->setName($base);
        $manager = new TrickSlugManager();
        $manager->setSlug($trick);

        $this->assertSame($expected, $trick->getSlug());
    }

    /**
     * @return array
     */
    public function nameProvider(): array
    {
        return [
            ['My amaZinG trick', 'my-amazing-trick'],
            ['TesttRick', 'testtrick']
        ];
    }
}
