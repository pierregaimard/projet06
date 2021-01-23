<?php

namespace App\Service\Security\EmailValidation;

use App\Entity\User;
use App\Service\Email\EmailManager;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EmailValidation
{
    /**
     * @var TimeManager
     */
    private TimeManager $timeManager;

    /**
     * @var TokenManager
     */
    private TokenManager $tokenManager;

    /**
     * @var UrlGenerator
     */
    private UrlGenerator $urlGenerator;

    /**
     * @var EmailManager
     */
    private EmailManager $emailManager;

    /**
     * @param TimeManager  $timeManager
     * @param TokenManager $tokenManager
     * @param UrlGenerator $urlGenerator
     * @param EmailManager $emailManager
     */
    public function __construct(
        TimeManager $timeManager,
        TokenManager $tokenManager,
        UrlGenerator $urlGenerator,
        EmailManager $emailManager
    ) {
        $this->timeManager  = $timeManager;
        $this->tokenManager = $tokenManager;
        $this->urlGenerator = $urlGenerator;
        $this->emailManager = $emailManager;
    }

    /**
     * @param User   $user
     * @param string $route
     * @param string $subject
     * @param string $template
     *
     * @throws TransportExceptionInterface
     */
    public function sendValidationLink(User $user, string $route, string $subject, string $template): void
    {
        $this->emailManager->send(
            $user->getEmail(),
            $subject,
            $template,
            [
                'firstName' => $user->getFirstName(),
                'url' => $this->urlGenerator->generateUrl($user, $route)
            ]
        );
    }

    /**
     * @param User   $user
     * @param string $token
     * @param string $expires
     *
     * @return bool
     */
    public function isTokenValid(User $user, string $token, string $expires): bool
    {
        // Link expiration verification
        if ($this->timeManager->hasExpired((int)$expires)) {
            return false;
        }

        // Token verification
        if (!$this->tokenManager->isTokenValid($token, $user)) {
            return false;
        }

        return true;
    }
}
