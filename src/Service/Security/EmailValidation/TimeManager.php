<?php

namespace App\Service\Security\EmailValidation;

class TimeManager
{
    /**
     * @var int
     */
    private const LIFETIME = 3600;

    /**
     * @return int
     */
    public function getExpires(): int
    {
        return time();
    }

    /**
     * @param int $time
     *
     * @return bool
     */
    public function hasExpired(int $time): bool
    {
        return ($time + self::LIFETIME) <= time();
    }
}
