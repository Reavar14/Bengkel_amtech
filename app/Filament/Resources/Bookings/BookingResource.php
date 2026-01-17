<?php

namespace App\Filament\Resources\Bookings;

use App\Models\Booking;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Tables\Columns\SelectColumn;
use App\Filament\Resources\Bookings\Pages;
use Carbon\Carbon;
use BackedEnum;

// FORM
use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-wrench';
    protected static ?string $navigationLabel = 'Booking';

    /** =======================
     *  PERMISSION
     *  ======================= */
    public static function canCreate(): bool
    {
        return auth()->check() && auth()->user()->hasRole('admin');
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    /** =======================
     *  FORM
     *  ======================= */
    public static function form(Schema $schema): Schema
    {
        return $schema->schema([

            TextInput::make('name')
                ->label('Nama Pelanggan')
                ->required(),

            TextInput::make('phone')
                ->label('No HP')
                ->required(),

            TextInput::make('email')
                ->label('Email')
                ->email()
                ->nullable(),

            Select::make('mechanic_id')
                ->label('Mekanik')
                ->relationship('mechanic', 'name')
                ->reactive()
                ->afterStateUpdated(fn ($set) => $set('time', null))
                ->required(),

            DatePicker::make('date')
                ->label('Tanggal')
                ->minDate(today())
                ->reactive()
                ->afterStateUpdated(fn ($set) => $set('time', null))
                ->required(),

            Select::make('time')
                ->label('Jam Servis')
                ->required()
                ->live()
                ->options(fn ($get) => self::getAvailableSlots(
                    $get('date'),
                    $get('mechanic_id')
                ))
                ->disabled(fn ($get) =>
                    ! $get('date') || ! $get('mechanic_id')
                ),

            TextInput::make('estimation')
                ->label('Estimasi (menit)')
                ->numeric()
                ->minValue(1)
                ->maxValue(480)
                ->nullable(),

            Textarea::make('notes')
                ->label('Catatan')
                ->nullable(),

            Select::make('status')
                ->label('Status')
                ->options(function (Get $get) {
                    if ($get('is_walk_in')) {
                        return [
                            'proses'     => 'Proses',
                            'selesai'    => 'Selesai',
                            'dibatalkan' => 'Dibatalkan',
                        ];
                    }

                    return [
                        'proses'     => 'Proses',
                        'selesai'    => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ];
                })
                ->required()
                ->reactive(),
        ]);
    }

    /** =======================
     *  SLOT LOGIC
     *  ======================= */
    public static function getAvailableSlots($date, $mechanicId): array
    {
        if (! $date || ! $mechanicId) {
            return [];
        }

        $slots = [
            '09.00-10.00',
            '11.00-12.00',
            '13.00-14.00',
            '15.00-16.00',
            '17.00-18.00',
        ];

        $bookedSlots = Booking::whereDate('date', $date)
            ->where('mechanic_id', $mechanicId)
            ->whereIn('status', ['menunggu', 'proses', 'selesai'])
            ->pluck('time')
            ->toArray();

        $now   = now('Asia/Jakarta');
        $today = $now->toDateString();

        $available = [];

        foreach ($slots as $slot) {
            [$start, $end] = explode('-', $slot);
            [$endH, $endM] = explode('.', $end);

            $slotEnd = Carbon::parse($date, 'Asia/Jakarta')
                ->setTime((int) $endH, (int) $endM);

            if ($date === $today && $now->greaterThanOrEqualTo($slotEnd)) {
                continue;
            }

            if (in_array($slot, $bookedSlots)) {
                continue;
            }

            $available[$slot] = str_replace('-', ' - ', $slot);
        }

        return $available;
    }

    /** =======================
     *  TABLE
     *  ======================= */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('booking_code')
                    ->label('Kode')
                    ->badge()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),

                TextColumn::make('mechanic.name')
                    ->label('Mekanik'),

                TextColumn::make('date')
                    ->label('Tanggal')
                    ->formatStateUsing(fn ($state) =>
                        Carbon::parse($state)->translatedFormat('d M Y')
                    ),

                TextColumn::make('time')
                    ->label('Jam'),

                TextInputColumn::make('estimation')
                    ->label('Estimasi (menit)')
                    ->type('number')
                    ->rules(['nullable', 'integer', 'min:1', 'max:480'])
                    ->disabled(fn ($record) =>
                        ! auth()->user()->hasRole('admin') ||
                        in_array($record->status, ['selesai', 'dibatalkan'])
                    ),

                SelectColumn::make('status')
                    ->label('Status')
                    ->options(fn ($record) =>
                        $record->is_walk_in
                            ? [
                                'proses'     => 'Proses',
                                'selesai'    => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ]
                            : [
                                'menunggu'   => 'Menunggu',
                                'proses'     => 'Proses',
                                'selesai'    => 'Selesai',
                                'dibatalkan' => 'Dibatalkan',
                            ]
                    )
                    ->disablePlaceholderSelection()
                    ->disabled(fn ($record) =>
                        ! auth()->user()->hasRole('admin')
                        || in_array($record->status, ['selesai', 'dibatalkan'])
                    ),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
        ];
    }
}