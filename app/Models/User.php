<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasNoPersonalTeam;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasNoPersonalTeam, HasTeams {
        HasNoPersonalTeam::ownsTeam insteadof HasTeams;
        HasNoPersonalTeam::isCurrentTeam insteadof HasTeams;
    }
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'login',
        'name',
        'email',
        'password',
    ];

    protected string $user_key = 'id';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    public function messages(): MorphMany
    {
        return $this->morphMany(Message::class, 'recipent');
    }

    public function sendedMessages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function messagesRead(): BelongsToMany
    {
        return $this->belongsToMany(Message::class, 'message_read');
    }

    public function allTeams()
    {
        return $this->ownedTeams()
            ->where('personal_team', false)
            ->get()
            ->sortBy('name');
    }

    public function pendingFriendsTo(): BelongsToMany
    {
        return $this->friendsTo()->wherePivot('accepted', false);
    }

    public function friendsTo(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
            ->using(Friend::class)
            ->withPivot('id')
            ->withTimestamps();
    }

    public function pendingFriendsFrom(): BelongsToMany
    {
        return $this->friendsFrom()->wherePivot('accepted', false);
    }

    public function friendsFrom(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id')
            ->using(Friend::class)
            ->withPivot('id')
            ->withTimestamps();
    }

    public function acceptedFriendsTo(): BelongsToMany
    {
        return $this->friendsTo()->wherePivot('accepted', true);
    }

    public function acceptedFriendsFrom(): BelongsToMany
    {
        return $this->friendsFrom()->wherePivot('accepted', true);
    }

    /**
     * @return Collection<int, User>
     */
    public function friends(): Collection
    {
        return $this->acceptedFriendsFrom->merge($this->acceptedFriendsTo);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function defaultProfilePhotoUrl(): string
    {
        $name = trim(collect(explode('.', $this->login))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }
}
