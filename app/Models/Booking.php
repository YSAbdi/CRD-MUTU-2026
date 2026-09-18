<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['guest_id','room_id','booking_code','check_in','check_out','status','notes','created_by'];
    protected $casts = ['check_in'=>'datetime','check_out'=>'datetime'];

    public function guest() { return $this->belongsTo(Guest::class); }
    public function room() { return $this->belongsTo(Room::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
    public function scopeActive(Builder $query): Builder { return $query->whereNotIn('status', ['cancelled','checked_out']); }
    public function scopeOverlapping(Builder $query, int $roomId, mixed $from, mixed $to): Builder { return $query->where('room_id',$roomId)->active()->where('check_in','<',$to)->where('check_out','>',$from); }
}
