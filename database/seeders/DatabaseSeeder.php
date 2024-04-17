<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\Team;
use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory(10)->create();

        $me = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $users = User::all();
        foreach ($users as $user) {
            Team::factory(2)->create([
                'user_id' => $user->id,
            ]);

            $random = random_int(0, 6);
            $list = [];
            for ($i = 0; $i < $random; $i++) {
                $friendId = User::get()->random()->id;
                if (! in_array($friendId, $list) && $user->id != $friendId) {
                    $user->acceptedFriendsTo()->attach($friendId, [
                        'accepted' => true,
                    ]);
                    $list[] = $friendId;
                }
            }
        }

        foreach (Team::all() as $team) {
            $usersId = [$team->user_id];
            for ($i = 0; $i < random_int(2, 6); $i++) {
                $id = $users->random()->id;
                while (in_array($id, $usersId)) {
                    $id = $users->random()->id;
                }
                $usersId[] = $id;
            }
            $team->users()->sync($usersId);
            for ($i = 0; $i < random_int(5, 25); $i++) {
                $message = new Message([
                    'user_id' => $team->users->random()->id,
                    'content' => app(Generator::class)->paragraph(),
                ]);
                $team->messages()->save($message);
            }
        }

        for ($i = 0; $i < 20; $i++) {
            $other = $me->friends()->random();
            $message = new Message([
                'user_id' => $other->id,
                'content' => app(Generator::class)->paragraph(),
            ]);
            $other->pivot->messages()->save($message);
        }
    }
}
