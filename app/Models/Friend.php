<?php

namespace App\Models;

use App\Contracts\Broadcaster;
use App\Events\GotMessage;
use App\Events\RemoveMessage;
use App\Events\UpdateMessage;
use App\Traits\HasMessage;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Friend extends Pivot implements Broadcaster
{
    use HasMessage;

    protected $table = 'friends';

    protected $fillable = ['id', 'user_id', 'friend_id', 'accepted'];

    protected static function booted(): void
    {
        static::deleting(function (Friend $friend) {
            $friend->messages()->delete();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function friend(): BelongsTo
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

    /**
     * @return PrivateChannel[]
     */
    public function channels(string $event, Message $message): array
    {
        return match ($event) {
            GotMessage::class => [
                new PrivateChannel('friend.'.$this->id),
                new PrivateChannel('App.Models.User.'.$this->getOtherUser($message->sender)->id),
            ],
            UpdateMessage::class, RemoveMessage::class => [
                new PrivateChannel('friend.'.$this->id),
            ]
        };
    }

    public function getOtherUser(User $user): User
    {
        if ($this->user_id == $user->id) {
            return $this->friend;
        }

        return $this->user;
    }

    public function notification(Message $message): array
    {
        return [
            'url' => route('friends.messages', ['friend' => $this->id]),
            'profile_photo_url' => $message->sender->profile_photo_url,
            'login' => $message->sender->login,
            'message' => 'Send a private message',
            'currentRoute' => true,
        ];
    }

    protected function casts(): array
    {
        return [
            'accepted' => 'bool',
        ];
    }
}
