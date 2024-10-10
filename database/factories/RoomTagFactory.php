<?php

declare(strict_types=1);

namespace TTBooking\SupportChat\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use TTBooking\SupportChat\Models\RoomTag;

/**
 * @extends Factory<RoomTag>
 */
class RoomTagFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<RoomTag>
     */
    protected $model = RoomTag::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tag' => fake()->words(2, true),
        ];
    }
}
