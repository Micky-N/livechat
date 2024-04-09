<?php

namespace App\Livewire\Forms;

use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Form;

class RoomForm extends Form
{
    public ?Team $room = null;

    #[Validate('required|max:20')]
    public string $name = '';

    public array $members = [];

    public function setRoom(Team $room)
    {
        $this->room = $room;

        $this->name = $room->name;

        $this->members = $room->users()->whereNot('user_id', Auth::id())->get()->all();
    }

    public function store(): void
    {
        $this->validate();

        /** @var User $user */
        $user = Auth::user();

        Team::create([
            'name' => $this->name,
            'user_id' => $user->id,
        ]);
    }

    public function update(): void
    {
        $this->validate();

        $this->room->update(
            $this->all()
        );

        $this->members[] = Auth::user();
        $this->room->users()->sync($this->membersId());
    }

    private function membersId(): array
    {
        return array_map(fn (User $user) => $user->id, $this->members);
    }
}
