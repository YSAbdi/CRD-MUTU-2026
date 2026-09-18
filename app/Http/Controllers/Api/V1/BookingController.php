<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Notifications\BookingStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function updateStatus(Request $request, Booking $booking)
    {
        $data = $request->validate(['status' => ['required', Rule::in(['pending','confirmed','checked_in','checked_out','cancelled'])]]);
        $booking->update($data);
        $booking->load(['guest','room','creator']);
        if ($booking->creator) $booking->creator->notify(new BookingStatusChanged($booking));
        return $booking;
    }
}
