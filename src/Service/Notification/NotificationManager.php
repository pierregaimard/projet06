<?php

namespace App\Service\Notification;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class NotificationManager
{
    /**
     * @var SessionInterface
     */
    private SessionInterface $session;

    /**
     * @var array
     */
    private array $container;

    /**
     * NotificationManager constructor.
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * @param Notification $notification
     */
    public function add(Notification $notification)
    {
        $this->container[] = $notification;
    }

    public function dispatch(): void
    {
        if (empty($this->container)) {
            return;
        }

        foreach ($this->container as $notification) {
            $this->session->getFlashBag()->add(Notification::KEY, $notification);
        }
    }
}
