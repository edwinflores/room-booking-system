<?php

namespace Database\Seeders;

use App\Enums\RoomType;
use App\Models\Room;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Create a batch of Rooms
 */
class RoomsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create room for each type
        $roomTypes = array_column(RoomType::cases(), 'value');
        foreach ($roomTypes as $roomType) {
            Room::factory()->count(3)->create([
                'type' => $roomType
            ]);
        }
    }
}
