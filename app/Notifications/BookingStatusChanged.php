<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class BookingStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;
    public function __construct(public Booking $booking) {}
    public function via(object $notifiable): array { return ['database','broadcast']; }
    public function toArray(object $notifiable): array { return ['booking_id'=>$this->booking->id,'booking_code'=>$this->booking->booking_code,'status'=>$this->booking->status,'message'=>'Status booking diperbarui.']; }
    public function toBroadcast(object $notifiable): BroadcastMessage { return new BroadcastMessage($this->toArray($notifiable)); }
}
