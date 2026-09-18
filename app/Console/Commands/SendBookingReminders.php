<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Notifications\BookingReminder;
use Illuminate\Console\Command;

class SendBookingReminders extends Command
{
    protected $signature = 'crm:booking-reminders';
    protected $description = 'Send queued reminders for upcoming check-in and check-out bookings';

    public function handle(): int
    {
        $windowStart = now()->addHours(23);
        $windowEnd = now()->addHours(25);
        $bookings = Booking::with('creator')->whereIn('status', ['confirmed','pending'])->whereBetween('check_in', [$windowStart, $windowEnd])->get();
        foreach ($bookings as $booking) if ($booking->creator) $booking->creator->notify(new BookingReminder($booking, 'check-in besok'));

        $checkoutBookings = Booking::with('creator')->where('status', 'checked_in')->whereBetween('check_out', [$windowStart, $windowEnd])->get();
        foreach ($checkoutBookings as $booking) if ($booking->creator) $booking->creator->notify(new BookingReminder($booking, 'check-out besok'));
        $this->info("Sent {$bookings->count()} check-in and {$checkoutBookings->count()} check-out reminders.");
        return self::SUCCESS;
    }
}
