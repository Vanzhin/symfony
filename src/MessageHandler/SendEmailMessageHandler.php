<?php

namespace App\MessageHandler;

use App\Message\SendEmailMessage;
use App\Service\Mailer;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class SendEmailMessageHandler implements MessageHandlerInterface
{


    private Mailer $mailer;

    public function __construct(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function __invoke(SendEmailMessage $message)
    {
        switch ($message->getType()) {
            case 'welcome':
                $this->mailer->sendWelcomeEmail($message);
                break;
            case 'verify':
                $this->mailer->sendVerificationEmail($message);
                break;
            default:
                $this->mailer->sendWelcomeEmail($message);
        }
    }
}
