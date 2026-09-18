<?php

namespace App\Notifications;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class BookingReminder extends Notification implements ShouldQueue
{
    use Queueable;
    public function __construct(public Booking $booking, public string $kind = 'check-in') {}
    public function via(object $notifiable): array { return ['database','broadcast']; }
    public function toArray(object $notifiable): array { return ['booking_id'=>$this->booking->id,'booking_code'=>$this->booking->booking_code,'kind'=>$this->kind,'message'=>"Pengingat {$this->kind} untuk booking {$this->booking->booking_code}."]; }
    public function toBroadcast(object $notifiable): BroadcastMessage { return new BroadcastMessage($this->toArray($notifiable)); }
}
