<?php

namespace App\Events;

use App\Models\TeamInvitation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AcceptRoomInvitation implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(private TeamInvitation $teamInvitation)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.'.$this->teamInvitation->user_id),
        ];
    }

    public function broadcastWith(): array
    {
        $owner = $this->teamInvitation->team->owner;

        return [
            'notification' => [
                'url' => route('rooms.index'),
                'profile_photo_url' => $owner->profile_photo_url,
                'login' => $owner->login,
                'message' => "Accepted your invitation for <span class='font-bold'>{$this->teamInvitation->team->name}</span>",
            ],
        ];
    }
}
