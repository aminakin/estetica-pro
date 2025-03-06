<?php

namespace Local\Notifications;

use Local\Notifications\IMessage;

interface IChannel
{
    public function send(): array;

    public function setMessage(IMessage $message);

    public function getName(): string;
}
