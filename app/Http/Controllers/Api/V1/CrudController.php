<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use App\Notifications\BookingStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CrudController extends Controller
{
    public function guests(Request $request) { return Guest::when($request->search, fn ($q, $s) => $q->where(fn ($x) => $x->where('name', 'like', "%{$s}%")->orWhere('identity_number', 'like', "%{$s}%")->orWhere('phone', 'like', "%{$s}%")))->latest()->paginate(min($request->integer('per_page', 25), 100)); }
    public function storeGuest(Request $request) { return response()->json(Guest::create($request->validate(['name'=>'required|string|max:150','identity_number'=>'required|string|max:40|unique:guests,identity_number','phone'=>'nullable|string|max:30','email'=>'nullable|email|max:150','notes'=>'nullable|string'])), 201); }
    public function updateGuest(Request $request, Guest $guest) { $guest->update($request->validate(['name'=>'sometimes|string|max:150','identity_number'=>['sometimes','string','max:40',Rule::unique('guests')->ignore($guest)],'phone'=>'nullable|string|max:30','email'=>'nullable|email|max:150','notes'=>'nullable|string'])); return $guest->fresh(); }
    public function deleteGuest(Guest $guest) { abort_if($guest->bookings()->exists(), 422, 'Tamu masih memiliki riwayat booking.'); $guest->delete(); return response()->noContent(); }
    public function rooms(Request $request) { return Room::when($request->search, fn ($q, $s) => $q->where(fn ($x) => $x->where('code','like',"%{$s}%")->orWhere('name','like',"%{$s}%")))->when($request->status, fn ($q, $s) => $q->where('status',$s))->latest()->paginate(min($request->integer('per_page', 25), 100)); }
    public function storeRoom(Request $request) { return response()->json(Room::create($request->validate(['code'=>'required|string|max:30|unique:rooms,code','name'=>'required|string|max:100','capacity'=>'required|integer|min:1','status'=>['required',Rule::in(['available','maintenance','inactive'])]])), 201); }
    public function updateRoom(Request $request, Room $room) { $room->update($request->validate(['code'=>['sometimes','string','max:30',Rule::unique('rooms')->ignore($room)],'name'=>'sometimes|string|max:100','capacity'=>'sometimes|integer|min:1','status'=>['sometimes',Rule::in(['available','maintenance','inactive'])]])); return $room->fresh(); }
    public function bookings(Request $request) { return Booking::with(['guest:id,name,identity_number','room:id,code,name'])->when($request->status, fn($q,$s) => $q->where('status',$s))->when($request->from, fn($q,$d) => $q->where('check_out','>=',$d))->when($request->to, fn($q,$d) => $q->where('check_in','<=',$d))->latest('check_in')->paginate(min($request->integer('per_page',25),100)); }
    public function storeBooking(Request $request) { $data=$request->validate(['guest_id'=>'required|exists:guests,id','room_id'=>'required|exists:rooms,id','check_in'=>'required|date|before:check_out','check_out'=>'required|date|after:check_in','status'=>['nullable',Rule::in(['pending','confirmed','checked_in','checked_out','cancelled'])],'notes'=>'nullable|string']); $booking=DB::transaction(function () use ($data,$request) { $room=Room::findOrFail($data['room_id']); abort_if($room->status !== 'available',422,'Kamar tidak tersedia.'); abort_if(Booking::where('room_id',$room->id)->whereNotIn('status',['cancelled','checked_out'])->where('check_in','<',$data['check_out'])->where('check_out','>',$data['check_in'])->exists(),422,'Kamar telah dipesan pada rentang tersebut.'); $data += ['booking_code'=>'MUTU-'.Str::upper(Str::random(8)),'created_by'=>$request->user()->id,'status'=>$data['status'] ?? 'pending']; return Booking::create($data)->load(['guest','room','creator']); }); if ($booking->creator) $booking->creator->notify(new BookingStatusChanged($booking)); return response()->json($booking,201); }
    public function updateBooking(Request $request, Booking $booking) { $data=$request->validate(['guest_id'=>'sometimes|exists:guests,id','room_id'=>'sometimes|exists:rooms,id','check_in'=>'sometimes|date|before:check_out','check_out'=>'sometimes|date|after:check_in','status'=>['sometimes',Rule::in(['pending','confirmed','checked_in','checked_out','cancelled'])],'notes'=>'nullable|string']); $roomId=$data['room_id']??$booking->room_id; $from=$data['check_in']??$booking->check_in; $to=$data['check_out']??$booking->check_out; abort_if(Booking::where('id','!=',$booking->id)->where('room_id',$roomId)->whereNotIn('status',['cancelled','checked_out'])->where('check_in','<',$to)->where('check_out','>',$from)->exists(),422,'Perubahan bertabrakan dengan booking lain.'); $booking->update($data); return $booking->fresh(['guest','room']); }
    public function deleteBooking(Booking $booking) { abort_if(in_array($booking->status,['checked_in','checked_out']),422,'Booking aktif/telah selesai tidak dapat dihapus.'); $booking->delete(); return response()->noContent(); }
}
