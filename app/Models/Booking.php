<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

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
    ];

    protected $casts = [
        'estimated_finish_time' => 'datetime',
        'actual_finish_time' => 'datetime',
        'release_time' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ✅ NAMA RELASI HARUS "mechanic"
    public function mechanic()
    {
        return $this->belongsTo(Mechanic::class, 'mechanic_id');
    }

    protected static function booted()
    {
        static::creating(function ($booking) {
            $booking->booking_code = 'BKG-' . now()->format('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        });
    }
}