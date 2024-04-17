<?php

namespace App\Events;

use App\Contracts\Broadcaster;
use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class AbstractMessageEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected Broadcaster $broadcaster;

    /**
     * Create a new event instance.
     */
    public function __construct(protected Message $message)
    {
        $this->broadcaster = $message->recipent;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel[]
     */
    public function broadcastOn(): array
    {
        return $this->broadcaster->channels(static::class, $this->message);
    }

    public function broadcastAs(): string
    {
        return $this->broadcaster->broadcastAs(static::class);
    }
}
