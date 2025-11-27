<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Database\Seeders;

use Illuminate\Auth\CreatesUserProviders;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use TTBooking\SupportChat\Models\Message;
use TTBooking\SupportChat\Models\Room;
use TTBooking\SupportChat\Models\RoomTag;
use TTBooking\SupportChat\SupportChat;

class DatabaseSeeder extends Seeder
{
    use CreatesUserProviders, WithoutModelEvents {
        getDefaultUserProvider as protected defaultUserProvider;
    }

    public function __construct(protected Container $app, protected Guard $auth) {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userModel = SupportChat::userModel();

        for ($i = 0; $i < 10; $i++) {
            $users = $userModel::all()->random(rand(1, 4))->push(...Arr::wrap($this->user()))->unique();

            Room::factory()
                ->recycle($users)
                ->hasAttached($users, [], 'users')
                ->has(RoomTag::factory()->count(3), 'tags')
                ->has(Message::factory()->recycle($users)->count(10))
                ->create();
        }
    }

    protected function user(): ?Authenticatable
    {
        if ($this->auth->hasUser()) {
            return $this->auth->user();
        }

        $credentials = (array) config('support-chat.seeding_credentials', ['email' => null]);

        foreach ($credentials as $credential => &$value) {
            $value ??= $this->command->outputComponents()->ask("Enter user $credential");
        }

        return tap(
            $this->createUserProvider()?->retrieveByCredentials($credentials),
            function (?Authenticatable $user) {
                $user || $this->command->outputComponents()->warn('User not found - proceeding anyway.');
            }
        );
    }

    public function getDefaultUserProvider(): string
    {
        return $this->defaultUserProvider() ?? 'users';
    }
}
