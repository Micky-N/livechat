<?php

namespace App\Contracts;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;

interface Broadcaster
{
    /**
     * @return Channel[]
     */
    public function channels(string $event, Message $message): array;

    public function notification(Message $message): array;
}
