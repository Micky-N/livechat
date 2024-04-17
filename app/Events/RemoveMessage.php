<?php

namespace App\Events;

class RemoveMessage extends AbstractMessageEvent
{
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
        ];
    }
}
