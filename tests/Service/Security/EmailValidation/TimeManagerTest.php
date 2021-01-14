<?php

namespace App\Tests\Service\Security\EmailValidation;

use App\Service\Security\EmailValidation\TimeManager;
use PHPUnit\Framework\TestCase;

class TimeManagerTest extends TestCase
{
    public function testHasExpired()
    {
        $manager = new TimeManager();
        $this->assertFalse($manager->hasExpired(time() - 3000));
        $this->assertTrue($manager->hasExpired(time() - 3700));
    }
}
