<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'name', 'capacity', 'status'];
    protected $casts = ['capacity' => 'integer'];
    public function bookings() { return $this->hasMany(Booking::class); }
}
