<?php

namespace App\Events;

use App\Models\Friend;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcceptFriendRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(private readonly Friend $friend)
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
            new PrivateChannel('App.Models.User.'.$this->friend->user_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'notification' => [
                'url' => route('requests.index'),
                'profile_photo_url' => $this->friend->friend->profile_photo_url,
                'login' => $this->friend->friend->login,
                'message' => 'Accepted your friend request',
            ],
        ];
    }
}
