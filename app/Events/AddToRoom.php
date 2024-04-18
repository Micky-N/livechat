<?php

namespace App\Events;

use App\Models\Team;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddToRoom implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(private readonly Team $room, private readonly User $user)
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
            new PrivateChannel('App.Models.User.'.$this->user->id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'notification' => [
                'url' => route('rooms.index'),
                'profile_photo_url' => $this->room->owner->profile_photo_url,
                'login' => $this->room->owner->login,
                'message' => "Added you to the chat room <span class='font-bold'>{$this->room->name}</span>",
            ],
        ];
    }
}
