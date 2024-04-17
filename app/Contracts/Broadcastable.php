<?php

namespace App\Contracts;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;

/**
 * @property string $broadcastPrefix
 */
interface Broadcastable
{
    /**
     * @return Channel[]
     */
    public function channels(Message $message): array;

    public function broadcastAs(string $event): string;
}
