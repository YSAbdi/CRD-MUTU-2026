<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $today = now()->startOfDay();
        $occupiedRooms = Booking::whereIn('status', ['confirmed', 'checked_in'])
            ->where('check_in', '<=', now())->where('check_out', '>', now())->distinct('room_id')->count('room_id');
        $roomCount = max(Room::where('status', 'available')->count(), 1);

        return response()->json([
            'period' => ['from' => $today->toDateString(), 'to' => now()->toDateString()],
            'bookings_today' => Booking::whereDate('check_in', $today)->count(),
            'checkins_today' => Booking::whereDate('check_in', $today)->whereIn('status', ['confirmed', 'checked_in'])->count(),
            'checkouts_today' => Booking::whereDate('check_out', $today)->whereIn('status', ['checked_in', 'checked_out'])->count(),
            'active_guests' => Booking::whereIn('status', ['confirmed', 'checked_in'])->count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'occupancy' => min(100, (int) round(($occupiedRooms / $roomCount) * 100)),
            'recent_bookings' => Booking::with(['guest:id,name', 'room:id,code,name'])->latest('check_in')->limit(10)->get(),
        ]);
    }
}
