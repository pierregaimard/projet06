<?php

namespace App\Service\Notification;

class Notification
{
    public const KEY          = 'notifications';
    public const TYPE_STD     = 'body';
    public const TYPE_SUCCESS = 'success';
    public const TYPE_WARNING = 'warning';
    public const TYPE_DANGER  = 'danger';
    public const TYPE_INFO    = 'info';

    /**
     * @var string
     */
    private string $type;

    /**
     * @var string
     */
    private string $message;

    /**
     * @var bool
     */
    private bool $autoHide = true;

    /**
     * @var int
     */
    private int $delay = 5000;

    /**
     * Notification constructor.
     *
     * @param string $message
     * @param string $type
     */
    public function __construct(string $message, string $type = self::TYPE_STD)
    {
        $this->type = $type;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return bool
     */
    public function isAutoHide(): bool
    {
        return $this->autoHide;
    }

    public function disableAutoHide(): void
    {
        $this->autoHide = false;
    }

    /**
     * @return int
     */
    public function getDelay(): int
    {
        return $this->delay;
    }

    /**
     * @param int $delay
     */
    public function setDelay(int $delay): void
    {
        $this->delay = $delay * 1000;
    }
}
