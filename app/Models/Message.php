<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'recipent_type', 'recipent_id', 'content', 'read_at'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function recipent(): MorphTo
    {
        return $this->morphTo();
    }

    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
        ];
    }

    public function readBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'message_read');
    }

    public function isMessageReadBy(User $user): bool
    {
        if ($user->id == $this->user_id) {
            return true;
        }

        return $this->readBy()->exists();
    }
}
