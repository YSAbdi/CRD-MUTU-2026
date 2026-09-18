<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\{Booking, Guest, Room};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $from = $request->date('from')?->startOfDay() ?? now()->startOfDay();
        return response()->json([
            'period' => ['from' => $from->toDateString(), 'to' => now()->toDateString()],
            'bookings_today' => Booking::whereDate('check_in', $from)->count(),
            'active_guests' => Booking::whereIn('status', ['confirmed', 'checked_in'])->count(),
            'available_rooms' => Room::where('status', 'available')->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'recent_bookings' => Booking::with(['guest:id,name', 'room:id,code'])->latest()->limit(10)->get(),
        ]);
    }
}
