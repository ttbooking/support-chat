<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use TTBooking\SupportChat\Models\Message;
use TTBooking\SupportChat\Models\Room;

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
        /** @var class-string<Model> $userModel */
        $userModel = config('support-chat.user_model');

        return [
            'room_id' => Room::factory(),
            'sender_id' => $userModel::all()->random()->getKey(),
            'content' => fake()->sentence(),
        ];
    }
}
