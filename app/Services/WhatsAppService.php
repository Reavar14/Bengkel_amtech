<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WhatsAppService
{
    public static function send(string $phone, string $message): bool
    {
        $response = Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN'),
        ])->post('https://api.fonnte.com/send', [
            'target'  => self::formatPhone($phone),
            'message' => $message,
        ]);

        return $response->successful();
    }

    protected static function formatPhone(string $phone): string
    {
        // 08xxxx → 628xxxx
        if (str_starts_with($phone, '08')) {
            return '62' . substr($phone, 1);
        }

        return $phone;
    }
}
