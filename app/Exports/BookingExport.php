<?php

namespace App\Exports;

use App\Models\Booking;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookingExport implements FromCollection, WithHeadings
{
    protected Request $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $query = Booking::with(['mechanic', 'user'])
            ->where('status', 'selesai');

        // ✅ FILTER MEKANIK (Filament tableFilters)
        if ($mechanicId = data_get($this->request->tableFilters, 'mechanic_id.value')) {
            $query->where('mechanic_id', $mechanicId);
        }

        // ✅ FILTER TANGGAL (Filament tableFilters)
        if ($date = data_get($this->request->tableFilters, 'date.date')) {
            $query->whereDate('date', $date);
        }

        return $query
            ->latest()
            ->get()
            ->map(function ($booking) {
                return [
                    'Kode Booking' => $booking->booking_code,
                    'Nama'         => $booking->user?->name ?? $booking->name ?? '-',
                    'Mekanik'      => $booking->mechanic?->name ?? '-',
                    'Tanggal'      => $booking->date,
                    'Jam'          => $booking->time,
                    'Status'       => ucfirst($booking->status),
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Kode Booking',
            'Nama',
            'Mekanik',
            'Tanggal',
            'Jam',
            'Status',
        ];
    }
}