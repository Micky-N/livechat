<?php

namespace App\Events;

use App\Models\Friend;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendFriendRequest implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(private Friend $friend)
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
            new PrivateChannel('App.Models.User.'.$this->friend->friend_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'notification' => [
                'url' => route('requests.index'),
                'profile_photo_url' => $this->friend->user->profile_photo_url,
                'login' => $this->friend->user->login,
                'message' => 'Sent you a friend request',
            ],
        ];
    }
}
