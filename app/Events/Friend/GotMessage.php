<?php

namespace App\Events\Friend;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GotMessage implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Message $message)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('friend-message.'.$this->getFriendId()),
        ];
    }

    private function getFriendId(): int
    {
        return $this->message->recipent_id;
    }

    public function broadcastAs(): string
    {
        return 'friend-got-message';
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->message->id,
        ];
    }
}
