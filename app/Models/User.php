<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\HasMessage;
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
    use HasMessage;
    use HasProfilePhoto;
    use HasTeams;
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
            ->get()->merge($this->teams()->where('personal_team', false)->get())
            ->sortBy('name');
    }

    protected function defaultProfilePhotoUrl(): string
    {
        $name = trim(collect(explode('.', $this->login))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get the user's "personal" team users.
     *
     * @return Collection<int, \App\Models\User>
     */
    public function personalTeamUsers(): Collection
    {
        return $this->personalTeam()->users()->whereNot('user_id', $this->id)->get();
    }
}
