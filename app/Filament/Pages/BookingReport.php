<?php

namespace App\Filament\Pages;

use App\Models\Booking;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;
use BackedEnum;

class BookingReport extends Page implements HasTable
{
    use InteractsWithTable;

    /** ✅ WAJIB DI FILAMENT v4 (NON STATIC) */
    protected string $view = 'filament.pages.booking-report';

    protected static UnitEnum|string|null $navigationGroup = 'Laporan';
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Laporan Booking';
    protected static ?string $title = 'Laporan Booking';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->url(fn () =>
                    route(
                        'admin.booking.pdf',
                        [
                            'tableFilters' => $this->getTableFiltersForm()->getState(),
                        ]
                    )
                )
                ->openUrlInNewTab(),

            Action::make('excel')
                ->label('Export Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->url(fn () => route(
                    'admin.booking.excel',
                    [
                        'tableFilters' => $this->getTableFiltersForm()->getState(),
                    ]
                ))
                ->openUrlInNewTab(),
        ];
    }

    /**
     * 🔥 SATU-SATUNYA TEMPAT QUERY DI FILAMENT v4
     */
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Booking::query()
                    ->with(['user', 'mechanic'])
                    ->where('status', 'selesai')
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('booking_code')
                    ->label('Kode Booking')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->getStateUsing(fn ($record) =>
                        $record->user?->name
                        ?? $record->name
                        ?? '-'
                    ),

                Tables\Columns\TextColumn::make('mechanic.name')
                    ->label('Mekanik')
                    ->default('-'),

                Tables\Columns\TextColumn::make('date')
                    ->label('Tanggal')
                    ->date('d M Y'),

                Tables\Columns\TextColumn::make('time')
                    ->label('Jam'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color('success'),
            ])
            ->filters([
                Filter::make('date')
                    ->form([
                        DatePicker::make('date')
                            ->label('Tanggal Booking'),
                    ])
                    ->query(fn (Builder $query, array $data) =>
                        $query->when(
                            $data['date'] ?? null,
                            fn ($q, $date) => $q->whereDate('date', $date)
                        )
                    ),

                SelectFilter::make('mechanic_id')
                    ->label('Mekanik')
                    ->relationship('mechanic', 'name')
                    ->searchable()
                    ->preload(),
            ]);
    }

    protected function getTableFiltersFormData(): array
    {
        return $this->tableFiltersForm?->getState() ?? [];
    }
}