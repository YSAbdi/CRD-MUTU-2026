<?php

namespace Database\Seeders;

use App\Models\{Booking, Guest, Room, User};
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'superadmin@mutu.co.id'],
            ['name' => 'Superadmin MUTU', 'password' => Hash::make(env('SUPERADMIN_PASSWORD', 'ChangeMe_123456!')), 'role' => 'superadmin', 'email_verified_at' => now()]
        );

        if (app()->environment('local', 'testing')) {
            User::factory()->count(3)->create();
            $rooms = Room::factory()->count(12)->create();
            $guests = Guest::factory()->count(20)->create();
            foreach ($guests->take(10) as $index => $guest) {
                $start = now()->addDays($index + 1)->setTime(14, 0);
                Booking::create([
                    'guest_id' => $guest->id, 'room_id' => $rooms[$index % $rooms->count()]->id,
                    'booking_code' => 'MUTU-DEMO-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT),
                    'check_in' => $start, 'check_out' => $start->copy()->addDays(2),
                    'status' => $index < 3 ? 'confirmed' : 'pending', 'created_by' => $admin->id,
                ]);
            }
        }
    }
}
