<?php

namespace App\Service\Email;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class EmailManager
{
    /**
     * @var MailerInterface
     */
    private MailerInterface $mailer;

    /**
     * @var string
     */
    private string $appEmail;

    /**
     * @param string          $appEmail
     * @param MailerInterface $mailer
     */
    public function __construct(string $appEmail, MailerInterface $mailer)
    {
        $this->appEmail = $appEmail;
        $this->mailer = $mailer;
    }

    /**
     * @param string $address
     * @param string $subject
     * @param string $template
     * @param array  $data
     *
     * @throws TransportExceptionInterface
     */
    public function send(string $address, string $subject, string $template, array $data = [])
    {
        $this->mailer->send((new TemplatedEmail())
            ->from($this->appEmail)
            ->to($address)
            ->subject($subject)
            ->htmlTemplate($template)
            ->context($data));
    }
}
