<?php

namespace Database\Factories;

use App\Models\{Booking, Guest, Room, User};
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Booking> */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        $checkIn = fake()->dateTimeBetween('+1 day', '+30 days');
        return [
            'guest_id' => Guest::factory(), 'room_id' => Room::factory(), 'booking_code' => 'MUTU-'.fake()->unique()->bothify('########'),
            'check_in' => $checkIn, 'check_out' => (clone $checkIn)->modify('+'.fake()->numberBetween(1, 5).' days'), 'status' => 'confirmed', 'notes' => fake()->optional()->sentence(), 'created_by' => User::factory(),
        ];
    }
}
