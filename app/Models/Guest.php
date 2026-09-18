<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guest extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'identity_number', 'phone', 'email', 'notes'];
    public function bookings() { return $this->hasMany(Booking::class); }
}
