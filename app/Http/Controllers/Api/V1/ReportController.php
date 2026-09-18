<?php

namespace App\Http\Controllers\Api\V1;

use App\Exports\BookingExport;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function bookings(Request $request)
    {
        $data = $request->validate(['format' => ['required','in:pdf,xlsx'], 'from' => ['nullable','date'], 'to' => ['nullable','date','after_or_equal:from']]);
        $export = new BookingExport($data['from'] ?? null, $data['to'] ?? null);
        if ($data['format'] === 'xlsx') return Excel::download($export, 'laporan-booking.xlsx');
        return Pdf::loadView('reports.bookings', ['rows' => $export->collection(), 'headings' => $export->headings()])->download('laporan-booking.pdf');
    }
}
