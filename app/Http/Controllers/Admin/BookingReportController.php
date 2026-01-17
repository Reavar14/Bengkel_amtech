<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingReportController extends Controller
{
    public function pdf(Request $request)
    {
        $query = Booking::with(['user', 'mechanic'])
            ->where('status', 'selesai');

        if ($mechanicId = data_get($request->tableFilters, 'mechanic_id.value')) {
            $query->where('mechanic_id', $mechanicId);
        }

        if ($date = data_get($request->tableFilters, 'date.date')) {
            $query->whereDate('date', $date);
        }

        $bookings = $query->latest()->get();

        // ✅ WAJIB ADA
        $pdf = Pdf::loadView('reports.booking-pdf', [
            'bookings' => $bookings,
        ])->setPaper('A4', 'portrait');

        // ✅ DOWNLOAD LANGSUNG
        return $pdf->download('laporan-booking.pdf');
    }

    public function excel(Request $request)
    {
        $query = Booking::with(['user', 'mechanic'])
            ->where('status', 'selesai');

        // ✅ FILTER MEKANIK
        if ($mechanicId = data_get($request->tableFilters, 'mechanic_id.value')) {
            $query->where('mechanic_id', $mechanicId);
        }

        // ✅ FILTER TANGGAL
        if ($date = data_get($request->tableFilters, 'date.date')) {
            $query->whereDate('date', $date);
        }

        $bookings = $query->latest()->get();

        // ✅ HEADER CSV
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=laporan-booking.csv",
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');

            // HEADER KOLOM
            fputcsv($file, [
                'Kode Booking',
                'Nama',
                'Mekanik',
                'Tanggal',
                'Jam',
                'Status',
            ]);

            // DATA
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->booking_code,
                    $booking->user?->name ?? $booking->name ?? '-',
                    $booking->mechanic?->name ?? '-',
                    $booking->date,
                    $booking->time,
                    $booking->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}