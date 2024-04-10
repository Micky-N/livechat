<?php

namespace App\Traits;

use App\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasMessage
{
    public function unReadMessages(): Collection
    {
        return $this->messages()->whereNot('user_id', auth()->id())->whereDoesntHave('readBy', function (Builder $query) {
            $query->where('user_id', $this->{$this->user_key});
        })->get();
    }

    public function messages(): MorphMany
    {
        return $this->morphMany(Message::class, 'recipent');
    }
}
