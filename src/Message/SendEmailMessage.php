<?php

namespace App\Message;

use App\Entity\User;

final class SendEmailMessage
{
    /*
     * Add whatever properties and methods you need
     * to hold the data for this message class.
     */

    private User $user;
    private ?string $template;
    private ?string $fromAddress;
    private ?string $fromName;
    private ?string $subject;
    private string $type;

    public function __construct(User $user, string $type, string $template = null, string $subject = null, string $fromAddress = null, string $fromName = null)
    {
        $this->user = $user;
        $this->template = $template;
        $this->fromAddress = $fromAddress;
        $this->fromName = $fromName;
        $this->subject = $subject;
        $this->type = $type;
    }

    /**
     * @return string|null
     */
    public function getSubject(): ?string
    {
        return $this->subject;
    }

    /**
     * @return string|null
     */
    public function getFromAddress(): ?string
    {
        return $this->fromAddress;
    }

    /**
     * @return string|null
     */
    public function getFromName(): ?string
    {
        return $this->fromName;
    }

    /**
     * @return string|null
     */
    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
