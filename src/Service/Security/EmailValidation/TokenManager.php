<?php

namespace App\Service\Security\EmailValidation;

use Symfony\Component\Security\Core\User\UserInterface;

class TokenManager
{
    /**
     * @var string
     */
    private string $secret;

    /**
     * @param $secret
     */
    public function __construct($secret)
    {
        $this->secret = $secret;
    }

    /**
     * @param string        $token
     * @param UserInterface $user
     *
     * @return bool
     */
    public function isTokenValid(string $token, UserInterface $user): bool
    {
        return hash_equals($this->getHash($user), base64_decode($token));
    }

    /**
     * @param UserInterface $user
     *
     * @return string
     */
    public function getToken(UserInterface $user): string
    {
        return base64_encode($this->getHash($user));
    }

    /**
     * @param UserInterface $user
     *
     * @return string
     */
    private function getHash(UserInterface $user): string
    {
        return urlencode(hash_hmac('sha256', $user->getUsername(), $this->secret, true));
    }
}
