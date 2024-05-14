<?php

namespace Database\Factories;

use App\Enums\RoomType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Room>
 */
class RoomFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = array_column(RoomType::cases(), 'value');
        $typeKey = array_rand($types);
        $type = $types[$typeKey];

        return [
            'name' => fake()->streetName(),
            'type' => $type,
        ];
    }
}
