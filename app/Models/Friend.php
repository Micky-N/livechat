<?php

namespace App\Models;

use App\Contracts\Broadcastable;
use App\Events\GotMessage;
use App\Events\RemoveMessage;
use App\Events\UpdateMessage;
use App\Traits\HasMessage;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Friend extends Pivot implements Broadcastable
{
    use HasMessage;

    protected $table = 'friends';

    protected $fillable = ['id', 'user_id', 'friend_id', 'accepted'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

    protected function casts(): array
    {
        return [
            'accepted' => 'bool',
        ];
    }

    public function getOtherUser(User $user): User
    {
        if ($this->user_id == $user->id) {
            return $this->friend;
        }

        return $this->user;
    }

    /**
     * @return PrivateChannel[]
     */
    public function channels(Message $message): array
    {
        return [
            new PrivateChannel('friend.'.$this->id),
            new PrivateChannel('App.Models.User.'.$this->getOtherUser($message->sender)->id),
        ];
    }

    public function broadcastAs(string $event): string
    {
        return match ($event) {
            GotMessage::class => 'got-message',
            UpdateMessage::class => 'update-message',
            RemoveMessage::class => 'remove-message'
        };
    }
}
