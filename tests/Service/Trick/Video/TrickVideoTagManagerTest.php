<?php

namespace App\Tests\Service\Trick\Video;

use App\Entity\TrickVideo;
use App\Service\Trick\Video\TrickVideoTagManager;
use PHPUnit\Framework\TestCase;
use Generator;

class TrickVideoTagManagerTest extends TestCase
{
    /**
     * @dataProvider tagIsValidProvider
     *
     * @param $actual
     * @param $expected
     */
    public function testIsValid($actual, $expected)
    {
        $manager = new TrickVideoTagManager();
        $this->assertSame($manager->isValid($actual), $expected);
    }

    /**
     * @return Generator
     */
    public function tagIsValidProvider(): Generator
    {
        yield ['<iframe width="245" height="500" src = "ddd" ', false];
        yield ['class="my-class" src="dsdfsjdf/dqsdsdijfsd"', true];
        yield ['id="dddd" class="ddsdsdf-sdq "', false];
    }

    /**
     * @dataProvider tagProvider
     *
     * @param $actual
     * @param $expected
     */
    public function testSetTag($actual, $expected)
    {
        $video = new TrickVideo();
        $video->setTag($actual);
        $manager = new TrickVideoTagManager();
        $manager->setTag($video);

        $this->assertSame($expected, $video->getTag());
    }

    /**
     * @return Generator
     */
    public function tagProvider(): Generator
    {
        yield ['id="ddd" src="https://www.youtube.com/embed/bruOUu2MpHM"', 'https://www.youtube.com/embed/bruOUu2MpHM'];
        yield ['id="ddd" src="https://www.youtube.com/embed/bUu2MpHM" ', 'https://www.youtube.com/embed/bUu2MpHM'];
    }
}
