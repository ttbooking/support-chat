<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Database\Seeders;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use TTBooking\SupportChat\Models\Message;
use TTBooking\SupportChat\Models\Room;
use TTBooking\SupportChat\Models\RoomTag;

class DatabaseSeeder extends Seeder
{
    //use WithoutModelEvents;

    public function __construct(protected UserProvider $userProvider) {}

    /**
     * Run the database seeds.
     */
    public function run(Guard $auth): void
    {
        /** @var class-string<Model&Authenticatable> $userModel */
        $userModel = config('support-chat.user_model');
        $users = $userModel::all()->random(3)->when($me = $auth->user() ?? $this->user())->add($me);

        Room::factory()
            ->hasAttached($users, [], 'users')
            ->has(RoomTag::factory()->count(3), 'tags')
            ->has(Message::factory()->recycle($users)->count(10))
            ->create();
    }

    protected function user(): ?Authenticatable
    {
        $credentials = (array) config('support-chat.seeding_credentials', ['email' => null]);

        foreach ($credentials as $credential => &$value) {
            $value ??= $this->command->ask("Enter user $credential:");
        }

        return tap($this->userProvider->retrieveByCredentials($credentials), function (?Authenticatable $user) {
            $user || $this->command->warn('User not found - proceeding anyway.');
        });
    }
}
