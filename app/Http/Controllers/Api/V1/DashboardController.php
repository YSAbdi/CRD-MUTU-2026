<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\{Booking, Room};
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request)
    {
        $today=now()->startOfDay();
        return response()->json(['period'=>['from'=>$today->toDateString(),'to'=>now()->toDateString()],
            'bookings_today'=>Booking::whereDate('check_in',$today)->count(),
            'checkins_today'=>Booking::whereDate('check_in',$today)->whereIn('status',['confirmed','checked_in'])->count(),
            'checkouts_today'=>Booking::whereDate('check_out',$today)->whereIn('status',['checked_in','checked_out'])->count(),
            'active_guests'=>Booking::active()->count(),'available_rooms'=>Room::where('status','available')->count(),
            'pending_bookings'=>Booking::where('status','pending')->count(),
            'recent_bookings'=>Booking::with(['guest:id,name','room:id,code'])->latest()->limit(10)->get()]);
    }
}
