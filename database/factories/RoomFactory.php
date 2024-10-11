<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use TTBooking\SupportChat\Models\Room;

/**
 * @extends Factory<Room>
 */
class RoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Room>
     */
    protected $model = Room::class;

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
            'name' => Str::ucfirst(fake()->words(3, true)),
            'created_by' => $this->getRandomRecycledModel($userModel)?->getKey() ?? $userModel::all()->random()->getKey(),
        ];
    }
}
