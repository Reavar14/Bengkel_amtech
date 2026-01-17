<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Services\WhatsAppService;
use Carbon\Carbon;

class SendBookingReminder extends Command
{
    protected $signature = 'booking:send-reminder';
    protected $description = 'Kirim WhatsApp reminder H-1 sebelum servis';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $bookings = Booking::with('mechanic')
            ->whereDate('date', $tomorrow)
            ->where('reminder_sent', false)
            ->whereNotIn('status', ['dibatalkan', 'selesai'])
            ->get();

        foreach ($bookings as $booking) {

            $message =
                "🔔 *REMINDER SERVIS*\n\n" .
                "Halo *{$booking->name}* 👋\n\n" .
                "Ini pengingat bahwa Anda memiliki jadwal servis *BESOK* 📆\n\n" .
                "📅 *Tanggal*  : {$booking->date}\n" .
                "⏰ *Jam*      : {$booking->time}\n" .
                "👨‍🔧 *Mekanik* : {$booking->mechanic->name}\n\n" .
                "Mohon hadir tepat waktu 🙏\n" .
                "Terima kasih 🚗✨";

            WhatsAppService::send(
                $booking->phone,
                $message
            );

            // Tandai sudah dikirim
            $booking->update([
                'reminder_sent' => true
            ]);
        }

        $this->info("Reminder WA terkirim: {$bookings->count()} booking");
    }
}