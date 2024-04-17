<?php

namespace App\Events;

class GotMessage extends AbstractMessageEvent
{
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
        ];
    }
}
