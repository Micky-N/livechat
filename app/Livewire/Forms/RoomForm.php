<?php

namespace App\Livewire\Forms;

use App\Events\AddToRoom;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
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
        $membersIds = $this->membersId();
        /** @var Collection<int, User> $oldUsers */
        $oldUsers = $this->room->users;
        foreach ($oldUsers as $oldUser) {
            if (! in_array($oldUser->id, $membersIds)) {
                $oldUser->sendedMessages()->where('recipent_id', $this->room->id)->where('recipent_type', Team::class)->delete();
            }
        }

        $newMembers = array_filter($membersIds, function (int $memberId) use ($oldUsers) {
            return ! $oldUsers->contains($memberId);
        });

        foreach ($newMembers as $newMember) {
            AddToRoom::dispatch($this->room, User::find($newMember));
        }
        $this->room->users()->sync($membersIds);
    }

    private function membersId(): array
    {
        return array_map(fn (User $user) => $user->id, $this->members);
    }
}
