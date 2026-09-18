<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\{Booking, Guest, Room};
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CrudController extends Controller
{
    public function guests(Request $request) { return Guest::latest()->paginate(25); }
    public function storeGuest(Request $request) { return Guest::create($request->validate(['name'=>'required|string|max:150','identity_number'=>'required|string|max:40|unique:guests,identity_number','phone'=>'nullable|string|max:30','email'=>'nullable|email','notes'=>'nullable|string'])); }
    public function rooms(Request $request) { return Room::latest()->paginate(25); }
    public function storeRoom(Request $request) { return Room::create($request->validate(['code'=>'required|string|max:30|unique:rooms,code','name'=>'required|string|max:100','capacity'=>'required|integer|min:1','status'=>['required',Rule::in(['available','maintenance','inactive'])]])); }
    public function bookings(Request $request) { return Booking::with(['guest:id,name','room:id,code,name'])->latest('check_in')->paginate(25); }
    public function storeBooking(Request $request) {
        $data=$request->validate(['guest_id'=>'required|exists:guests,id','room_id'=>'required|exists:rooms,id','check_in'=>'required|date|before:check_out','check_out'=>'required|date|after:check_in','notes'=>'nullable|string']);
        abort_if(Booking::where('room_id',$data['room_id'])->whereNotIn('status',['cancelled','checked_out'])->where('check_in','<',$data['check_out'])->where('check_out','>',$data['check_in'])->exists(),422,'Kamar telah dipesan pada rentang tersebut.');
        $data += ['booking_code'=>'MUTU-'.Str::upper(Str::random(8)),'created_by'=>$request->user()->id];
        return response()->json(Booking::create($data)->load(['guest','room']),201);
    }
}
