<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BookingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $from = Carbon::now()->addHours(1);
        $to = Carbon::now()->addHours(2);

        return [
            'user_id' => User::factory(),
            'room_id' => Room::factory(),
            'reserved_from' => $from,
            'reserved_to' => $to,
        ];
    }
}
