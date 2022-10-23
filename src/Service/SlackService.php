<?php

namespace App\Service;

use App\Helper\LoggerTrait;
use Symfony\Component\Notifier\ChatterInterface;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Notifier\Message\ChatMessage;

class SlackService

{
    use LoggerTrait;

    private ChatterInterface $chatter;

    public function __construct(ChatterInterface $chatter)
    {
        $this->chatter = $chatter;
    }

    public function send(string $message): void
    {
        $chatMessage = new ChatMessage($message);

        try {
            $this->chatter->send($chatMessage);

        } catch (TransportExceptionInterface $error) {
            $error->getMessage();
        } finally {
            if ($this->logger) {
                $this->logInfo('отправелно через слэк: ' . $message);
            }
        }


    }

}