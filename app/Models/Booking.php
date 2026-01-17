<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Services\WhatsAppService;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'email',
        'mechanic_id',
        'date',
        'time',
        'notes',
        'status',
        'estimation',
        'estimated_finish',
        'mechanic_notes',
        'release_time',
        'is_walk_in',
    ];

    protected $casts = [
        'estimated_finish' => 'datetime',
        'release_time'     => 'datetime',
        'is_walk_in'       => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class);
    }

    protected static function booted()
    {
        static::creating(function ($booking) {
            if (! $booking->booking_code) {
                $booking->booking_code =
                    'BKG-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
            }

            if ($booking->is_walk_in) {
                $booking->status = 'proses';
            } elseif (! $booking->status) {
                $booking->status = 'menunggu';
            }
        });

        static::saving(function ($booking) {
            if ($booking->is_walk_in) {
                if (! in_array($booking->status, ['proses', 'selesai', 'dibatalkan'])) {
                    throw new \Exception('Status tidak valid untuk Walk In');
                }
            }

            if (
                filled($booking->estimation) &&
                filled($booking->time) &&
                filled($booking->date)
            ) {
                try {
                    [$start] = explode('-', $booking->time);
                    [$h, $m] = explode('.', trim($start));

                    $startTime = \Carbon\Carbon::createFromFormat(
                        'Y-m-d H:i',
                        $booking->date . ' ' . $h . ':' . $m
                    );

                    $booking->estimated_finish =
                        $startTime->addMinutes((int) $booking->estimation);
                } catch (\Throwable $e) {}
            }
        });

        static::updated(function ($booking) {
            if ($booking->wasChanged('status') && $booking->status === 'selesai') {

                $message =
                    "🔧 *SERVIS SELESAI*\n\n" .
                    "Halo *{$booking->name}* 👋\n\n" .
                    "Servis kendaraan Anda telah *SELESAI* ✅\n\n" .
                    "📅 Tanggal  : {$booking->date}\n" .
                    "⏰ Jam      : {$booking->time}\n" .
                    "👨‍🔧 Mekanik : {$booking->mechanic->name}\n\n" .
                    "Terima kasih 🙏";

                WhatsAppService::send($booking->phone, $message);
            }
        });
    }
}