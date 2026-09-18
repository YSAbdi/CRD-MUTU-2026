<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        $data=$request->validate(['start'=>'required|date','end'=>'required|date|after_or_equal:start']);
        return Booking::with(['guest:id,name','room:id,code,name'])->where('check_in','<',$data['end'])->where('check_out','>',$data['start'])->whereNotIn('status',['cancelled'])->get()->map(fn($b)=>[
            'id'=>$b->id,'title'=>($b->guest?->name ?? 'Tamu').' · '.($b->room?->code ?? 'Kamar'),'start'=>$b->check_in->toIso8601String(),'end'=>$b->check_out->toIso8601String(),'status'=>$b->status,'booking_code'=>$b->booking_code,
        ]);
    }
}
