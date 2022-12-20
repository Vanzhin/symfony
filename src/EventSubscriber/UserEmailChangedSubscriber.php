<?php

namespace App\EventSubscriber;

use App\Event\UserEmailChangedEvent;
use App\Event\UserRegistrationEvent;
use App\Message\SendEmailMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UserEmailChangedSubscriber implements EventSubscriberInterface
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserEmailChangedEvent::class => 'onUserEmailChanged',
        ];
    }

    public function onUserEmailChanged(UserEmailChangedEvent $event)
    {
        $user = $event->getUser();
        $user->setIsVerified(false);
        $this->messageBus->dispatch(new SendEmailMessage($user, 'verify'));

    }
}