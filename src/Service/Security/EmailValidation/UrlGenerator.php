<?php

namespace App\Service\Security\EmailValidation;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UrlGenerator
{
    public const KEY_TOKEN = 'token';
    public const KEY_EXPIRES = 'expires';

    /**
     * @var UrlGeneratorInterface
     */
    private UrlGeneratorInterface $urlGenerator;

    /**
     * @var TokenManager
     */
    private TokenManager $tokenManager;

    /**
     * @var TimeManager
     */
    private TimeManager $timeManager;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param TokenManager          $tokenManager
     * @param TimeManager           $timeManager
     */
    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        TokenManager $tokenManager,
        TimeManager $timeManager
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->tokenManager = $tokenManager;
        $this->timeManager  = $timeManager;
    }

    /**
     * @param UserInterface $user
     * @param string        $routeName
     *
     * @return string
     */
    public function generateUrl(UserInterface $user, string $routeName): string
    {
        return $this->urlGenerator->generate(
            $routeName,
            [
                self::KEY_TOKEN => $this->tokenManager->getToken($user),
                self::KEY_EXPIRES => $this->timeManager->getExpires()
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
    }
}
