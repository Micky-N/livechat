<?php

namespace App\Traits;

use App\Models\Message;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

trait HasMessage
{
    public function messages(): MorphMany
    {
        return $this->morphMany(Message::class, 'recipent');
    }

    public function unReadMessages(): Collection
    {
        return $this->messages->where(function (Message $message) {
            return ! $message->isMessageReadBy(Auth::user());
        });
    }
}
