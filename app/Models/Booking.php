<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = ['guest_id', 'room_id', 'booking_code', 'check_in', 'check_out', 'status', 'notes', 'created_by'];
    protected $casts = ['check_in' => 'datetime', 'check_out' => 'datetime'];
    public function guest() { return $this->belongsTo(Guest::class); }
    public function room() { return $this->belongsTo(Room::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}
