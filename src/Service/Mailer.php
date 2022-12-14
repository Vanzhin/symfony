<?php

namespace App\Service;

use App\Entity\User;
use App\Message\SendEmailMessage;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class Mailer
{

    private MailerInterface $mailer;
    private string $appName;
    private string $defaultFromEmail;
    private string $defaultFromName;
    private EmailVerifier $emailVerifier;


    public function __construct(MailerInterface $mailer, EmailVerifier $emailVerifier, string $appName, string $defaultFromEmail, string $defaultFromName)
    {
        $this->mailer = $mailer;
        $this->appName = $appName;
        $this->defaultFromEmail = $defaultFromEmail;
        $this->defaultFromName = $defaultFromName;
        $this->emailVerifier = $emailVerifier;
    }

    public function sendWelcomeEmail(SendEmailMessage $message): void
    {
        $this->send(
            $message->getTemplate() ?? 'emails/welcome.html.twig',
            $message->getFromAddress() ?? 'welcome@test.ru',
            $message->getFromName() ?? $this->defaultFromName,
            $message->getUser(),
            $message->getSubject() ?? 'Welcome to ' . $this->appName,
            function ($email) use ($message) {
                $email->context([
                    'user' => $message->getUser(),
                    'appName' => $this->appName
                ]);
            }
        );
    }

    public function sendVerificationEmail(SendEmailMessage $message): void
    {
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $message->getUser(),
            $this->email(
                'emails/confirmation_email.html.twig',
                $message->getFromAddress() ?? 'noreplye@test.ru',
                $message->getFromName() ?? $this->defaultFromName,
                $message->getUser(),
                $message->getSubject() ?? 'Email verification from ' . $this->appName,
            )
        );
    }

    private function send(string $template, string $fromEmail, string $fromName, User $user, string $subject, \Closure $callback = null): void
    {
        $email = $this->email($template, $fromEmail, $fromName, $user, $subject);


        if ($callback) {
            $callback($email);
        }
        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $exception) {
            $exception->getMessage();
            return;
        }
    }

    private function email(string $template, string $fromEmail, string $fromName, User $user, string $subject): TemplatedEmail
    {
        return (new TemplatedEmail())
            ->from(new Address($fromEmail, $fromName))
            ->to(new Address($user->getEmail(), $user->getName()))
            ->subject($subject)
            ->htmlTemplate($template);
    }
}