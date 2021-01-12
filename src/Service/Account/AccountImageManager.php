<?php

namespace App\Service\Account;

use App\Entity\User;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;

class AccountImageManager
{
    /**
     * @var string
     */
    private string $accountImageDir;

    /**
     * @var Filesystem
     */
    private Filesystem $filesystem;

    public function __construct(string $accountImageDir, Filesystem $filesystem)
    {
        $this->accountImageDir = $accountImageDir;
        $this->filesystem = $filesystem;
    }

    /**
     * @param User $user
     *
     * @return string
     */
    public function getFileName(User $user): string
    {
        return $user->getId() . '.jpeg';
    }

    /**
     * @param File $file
     * @param User $user
     */
    public function save(File $file, User $user): void
    {
        $file->move($this->accountImageDir, $this->getFileName($user));
    }

    /**
     * @param User $user
     */
    public function remove(User $user): void
    {
        $this->filesystem->remove($this->accountImageDir . $user->getPicture());
    }
}
