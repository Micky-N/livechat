<?php

namespace App\Events;

use App\Contracts\Broadcastable;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class AbstractMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Broadcastable $broadcastable;

    /**
     * Create a new event instance.
     */
    public function __construct(public Message $message)
    {
        $this->broadcastable = $message->recipent;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return $this->broadcastable->channels($this->message);
    }

    public function broadcastAs(): string
    {
        return $this->broadcastable->broadcastAs(static::class);
    }
}
