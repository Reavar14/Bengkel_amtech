<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BookingExport;
use Illuminate\Http\Request;

class BookingExportController extends Controller
{
    public function pdf()
    {
        $bookings = Booking::with('mechanic')->latest()->get();

        $pdf = Pdf::loadView('reports.booking-pdf', compact('bookings'));

        return $pdf->download('laporan-booking.pdf');
    }

    public function excel(Request $request)
    {
        $bookings = Booking::with(['mechanic', 'user'])
            ->where('status', 'selesai')
            ->when($request->date, fn ($q) =>
                $q->whereDate('date', $request->date)
            )
            ->when($request->mechanic_id, fn ($q) =>
                $q->where('mechanic_id', $request->mechanic_id)
            )
            ->latest()
            ->get();

        return response()->stream(function () use ($bookings) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Kode Booking',
                'Nama',
                'Mekanik',
                'Tanggal',
                'Jam',
                'Status',
            ]);

            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->booking_code,
                    $booking->user?->name ?? $booking->name ?? '-',
                    $booking->mechanic?->name ?? '-',
                    $booking->date,
                    $booking->time,
                    ucfirst($booking->status),
                ]);
            }

            fclose($file);
        }, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename=laporan-booking.csv',
        ]);
    }
}
