<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\{Booking, Guest, Room};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        return Booking::with(['guest:id,name', 'room:id,code,name'])
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->from, fn ($q, $d) => $q->where('check_out', '>=', $d))
            ->when($request->to, fn ($q, $d) => $q->where('check_in', '<=', $d))
            ->latest('check_in')->paginate(25);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'guest_id' => ['required', Rule::exists(Guest::class, 'id')],
            'room_id' => ['required', Rule::exists(Room::class, 'id')],
            'check_in' => ['required', 'date', 'before:check_out'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'notes' => ['nullable', 'string'],
        ]);
        $conflict = Booking::where('room_id', $data['room_id'])->whereNotIn('status', ['cancelled', 'checked_out'])
            ->where('check_in', '<', $data['check_out'])->where('check_out', '>', $data['check_in'])->exists();
        abort_if($conflict, 422, 'Kamar telah dipesan pada rentang waktu tersebut.');
        $data['booking_code'] = 'MUTU-' . Str::upper(Str::random(8));
        $data['created_by'] = $request->user()->id;
        $booking = Booking::create($data);
        return response()->json($booking->load(['guest', 'room']), 201);
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $data = $request->validate(['status' => ['required', Rule::in(['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'])]]);
        $booking->update($data);
        return $booking->fresh(['guest', 'room']);
    }
}
