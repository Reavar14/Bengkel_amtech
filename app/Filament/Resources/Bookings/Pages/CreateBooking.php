<?php

namespace App\Filament\Resources\Bookings\Pages;

use App\Filament\Resources\Bookings\BookingResource;
use Filament\Resources\Pages\CreateRecord;
use App\Mail\BookingCreatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // WALK-IN → tidak punya akun user
        $data['user_id'] = null;
        $data['status'] = 'proses';

        return $data;
    }

    protected function beforeCreate(): void
    {
        $exists = \App\Models\Booking::whereDate('date', $this->data['date'])
            ->where('mechanic_id', $this->data['mechanic_id'])
            ->where('time', $this->data['time'])
            ->whereIn('status', ['menunggu', 'proses'])
            ->exists();

        if ($exists) {
            throw ValidationException::withMessages([
                'time' => 'Slot ini sudah dibooking.',
            ]);
        }
    }

    protected function afterCreate(): void
    {
        if ($this->record->email) {
            Mail::to($this->record->email)
                ->send(new BookingCreatedMail($this->record));
        }
    }
}