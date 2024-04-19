<?php

namespace App\Events;

use App\Models\Team;
use App\Models\TeamInvitation;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SendRoomInvitation implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private Team $team;

    /**
     * Create a new event instance.
     */
    public function __construct(private TeamInvitation $teamInvitation)
    {
        $this->team = $this->teamInvitation->team;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.'.$this->team->user_id),
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'notification' => [
                'url' => route('rooms.invitations.index'),
                'profile_photo_url' => $this->teamInvitation->user->profile_photo_url,
                'login' => $this->teamInvitation->user->login,
                'message' => "Sent you an invitation for <span class='font-bold'>{$this->team->name}</span>",
            ],
        ];
    }
}
