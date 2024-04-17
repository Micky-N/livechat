<?php

namespace App\Models;

use App\Contracts\Broadcaster;
use App\Events\GotMessage;
use App\Events\RemoveMessage;
use App\Events\UpdateMessage;
use App\Traits\HasMessage;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Jetstream\Events\TeamCreated;
use Laravel\Jetstream\Events\TeamDeleted;
use Laravel\Jetstream\Events\TeamUpdated;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Team as JetstreamTeam;

class Team extends JetstreamTeam implements Broadcaster
{
    use HasFactory;
    use HasMessage;

    protected string $broadcastPrefix = 'room.';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'personal_team',
    ];

    protected string $user_key = 'user_id';

    /**
     * The event map for the model.
     *
     * @var array<string, class-string>
     */
    protected $dispatchesEvents = [
        'created' => TeamCreated::class,
        'updated' => TeamUpdated::class,
        'deleted' => TeamDeleted::class,
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(Jetstream::userModel(), 'user_id');
    }

    public function members(): BelongsToMany
    {
        return $this->users()->whereNot('user_id', $this->user_id);
    }

    protected static function booted(): void
    {
        static::deleting(function (Team $team) {
            $team->messages()->delete();
        });
        static::created(function (Team $team) {
            $team->members()->attach($team->user_id);
        });
    }

    /**
     * @return PrivateChannel[]
     */
    public function channels(string $event, Message $message): array
    {
        $channels = [new PresenceChannel('room.'.$this->id)];
        foreach ($this->users()->withPivot('notify')->get() as $user) {
            if ($user->membership->silent) {
                continue;
            }
            $channels[] = new PrivateChannel('App.Models.User.'.$user->id);
        }

        return match ($event) {
            GotMessage::class => $channels,
            UpdateMessage::class, RemoveMessage::class => [
                new PresenceChannel('room.'.$this->id),
            ]
        };
    }

    public function broadcastAs(string $event): string
    {
        return match ($event) {
            GotMessage::class => 'got-message',
            UpdateMessage::class => 'update-message',
            RemoveMessage::class => 'remove-message'
        };
    }

    public function notification(Message $message): array
    {
        return [
            'url' => route('rooms.messages', ['room' => $this->id]),
            'profile_photo_url' => $message->sender->profile_photo_url,
            'login' => $message->sender->login,
            'message' => "Send a message in <span class='font-bold'>$this->name</span>",
        ];
    }
}
