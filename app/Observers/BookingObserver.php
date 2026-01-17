<?php

namespace App\Observers;

use App\Models\Booking;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class BookingObserver
{
    public function updated(Booking $booking)
    {
        if (
            $booking->isDirty('status') &&
            $booking->status === 'dibatalkan'
        ) {
            WhatsAppService::send(
                $booking->phone,
                "❌ *Booking Dibatalkan*\n\nKode: {$booking->booking_code}"
            );
        }
    }

    public function created(Booking $booking): void
    {
        // Jika tidak ada nomor WA, hentikan
        if (!$booking->phone) {
            return;
        }

        // Ambil status REAL dari database
        $statusLabel = match ($booking->status) {
            'proses'   => 'Proses',
            'selesai'  => 'Selesai',
            'batal'    => 'Dibatalkan',
            default    => 'Menunggu',
        };

        $message =
            "📌 *Booking Servis Berhasil*\n\n" .
            "Kode Booking: {$booking->booking_code}\n" .
            "Nama: {$booking->name}\n" .
            "Tanggal: " . Carbon::parse($booking->date)->format('d M Y') . "\n" .
            "Jam: {$booking->time}\n" .
            "Mekanik: " . optional($booking->mechanic)->name . "\n\n" .
            "Status: *{$statusLabel}*\n\n" .
            "Terima kasih telah melakukan reservasi di\n" .
            "*Anugrah Manual Tech*";

        WhatsAppService::send($booking->phone, $message);
    }
}