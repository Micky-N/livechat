<?php

namespace App\Events;

class UpdateMessage extends AbstractMessageEvent
{
    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
            'content' => $this->message->content,
        ];
    }
}
