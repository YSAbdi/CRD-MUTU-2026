<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookingExport implements FromCollection, WithHeadings
{
    public function __construct(private readonly ?string $from = null, private readonly ?string $to = null) {}
    public function collection() { return Booking::with(['guest:id,name,identity_number','room:id,code,name'])->when($this->from, fn ($q) => $q->whereDate('check_in', '>=', $this->from))->when($this->to, fn ($q) => $q->whereDate('check_out', '<=', $this->to))->latest('check_in')->get()->map(fn ($b) => [$b->booking_code, $b->guest?->name, $b->guest?->identity_number, $b->room?->code, $b->check_in?->format('Y-m-d H:i'), $b->check_out?->format('Y-m-d H:i'), $b->status]); }
    public function headings(): array { return ['Kode Booking','Nama Tamu','Nomor Identitas','Kamar','Check In','Check Out','Status']; }
}
