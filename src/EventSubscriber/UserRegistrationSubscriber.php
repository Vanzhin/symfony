<?php

namespace App\EventSubscriber;

use App\Event\UserRegistrationEvent;
use App\Message\SendEmailMessage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UserRegistrationSubscriber implements EventSubscriberInterface
{

    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            UserRegistrationEvent::class => 'onUserRegistration',
        ];
    }

    public function onUserRegistration(UserRegistrationEvent $event)
    {
        $this->messageBus->dispatch(new SendEmailMessage($event->getUser(), 'welcome'));
        $this->messageBus->dispatch(new SendEmailMessage($event->getUser(), 'verify'));
    }
}