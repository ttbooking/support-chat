<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TTBooking\SupportChat\Models\Message;
use TTBooking\SupportChat\Models\Room;
use TTBooking\SupportChat\SupportChat;

/**
 * @extends Factory<Message>
 */
class MessageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Message>
     */
    protected $model = Message::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userModel = SupportChat::userModel();

        $room = $this->getRandomRecycledModel(Room::class);

        return [
            'room_id' => $room?->getKey() ?? Room::factory(),
            'sent_by' => $this->getRandomRecycledModel($userModel)?->getKey()
                ?? $room?->users->random()->getKey()
                ?? $userModel::all()->random()->getKey(),
            'content' => fake()->sentence(),
        ];
    }
}
