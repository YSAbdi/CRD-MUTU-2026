<?php

namespace Database\Factories;

use App\Models\Room;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Room> */
class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        $number = fake()->unique()->numberBetween(1, 999);
        return ['code' => 'R-'.str_pad((string) $number, 3, '0', STR_PAD_LEFT), 'name' => 'Room '.$number, 'capacity' => fake()->numberBetween(1, 8), 'status' => 'available'];
    }
}
