<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $data = $request->validate([
            'start' => ['required', 'date'],
            'end' => ['required', 'date', 'after:start'],
        ]);

        return Booking::with(['guest:id,name', 'room:id,code,name'])
            ->where('check_in', '<', $data['end'])
            ->where('check_out', '>', $data['start'])
            ->whereNotIn('status', ['cancelled'])
            ->orderBy('check_in')
            ->get()
            ->map(fn (Booking $booking) => [
                'id' => $booking->id,
                'title' => trim(($booking->guest?->name ?? 'Tamu') . ' · ' . ($booking->room?->code ?? 'Kamar')),
                'start' => $booking->check_in->toIso8601String(),
                'end' => $booking->check_out->toIso8601String(),
                'status' => $booking->status,
                'booking_code' => $booking->booking_code,
            ]);
    }
}
