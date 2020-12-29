<?php

namespace App\Service\Security\EmailValidation;

class TimeManager
{
    /**
     * @var int
     */
    private int $lifetime;

    /**
     * @param $lifetime
     */
    public function __construct($lifetime)
    {
        $this->lifetime = (int)$lifetime;
    }

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
        return ($time + $this->lifetime) <= time();
    }
}
