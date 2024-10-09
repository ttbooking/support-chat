<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use TTBooking\SupportChat\Models\Message;
use TTBooking\SupportChat\Models\Room;
use TTBooking\SupportChat\Models\RoomTag;

class DatabaseSeeder extends Seeder
{
    //use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /** @var class-string<Model> $userModel */
        $userModel = config('support-chat.user_model');
        $users = $userModel::all()->random(3);

        Room::factory()
            ->hasAttached($users, [], 'users')
            ->has(RoomTag::factory()->count(3), 'tags')
            ->has(Message::factory()->recycle($users)->count(10))
            ->create();
    }
}
