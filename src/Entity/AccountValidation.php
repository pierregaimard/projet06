<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class AccountValidation
{
    /**
     * @var string
     * @Assert\NotBlank
     */
    private string $username;

    /**
     * @var string
     * @Assert\NotBlank
     */
    private string $password;

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}
