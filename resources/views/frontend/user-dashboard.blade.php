@extends('layouts.frontend-layout')

@section('title', 'Dashboard Saya')

@section('content')
<div class="container py-5">

    <h2 class="mb-4">Booking Saya</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($bookings->isEmpty())
        <div class="alert alert-info">
            Belum ada booking.
        </div>
    @else

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th style="width: 50px;">No</th>
                    <th>Kode</th>
                    <th>Waktu & Nama</th>
                    <th>Mekanik</th>
                    <th>Estimasi</th>
                    <th>Status</th>
                    <th>Keluhan</th>
                    <th style="width: 120px;">Aksi</th>
                </tr>
            </thead>

            <tbody>
            @foreach($bookings as $index => $booking)
                <tr>
                    {{-- No --}}
                    <td>{{ $index + 1 }}</td>

                    {{-- Booking Code --}}
                    <td>
                        <span class="badge bg-dark">{{ $booking->booking_code }}</span>
                    </td>

                    {{-- Waktu + nama --}}
                    <td>
                        <strong>
                            {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}
                            {{ substr($booking->time, 0, 5) }}
                        </strong>
                        <br>
                        {{ $booking->name }}
                    </td>

                    {{-- Mekanik --}}
                    <td>
                        {{ $booking->mechanic?->name ?? '-' }}
                    </td>

                    {{-- Estimasi --}}
                    <td>
                        {{ $booking->estimation ?? '-' }}
                    </td>

                    {{-- Status --}}
                    <td>
                        @switch($booking->status)
                            @case('pending')
                                <span class="badge bg-warning text-dark">Menunggu</span>
                            @break

                            @case('proses')
                                <span class="badge bg-info text-dark">Diproses</span>
                            @break

                            @case('selesai')
                                <span class="badge bg-success text-white">Selesai</span>
                            @break

                            @case('dibatalkan')
                                <span class="badge bg-danger text-white">Dibatalkan</span>
                            @break

                            @default
                                <span class="badge bg-secondary">-</span>
                        @endswitch
                    </td>

                    {{-- Keluhan --}}
                    <td>{{ $booking->notes ?? '-' }}</td>

                    {{-- Aksi --}}
                    <td>
                        @if($booking->status == 'pending')
                            <form action="{{ route('booking.cancel', $booking->id) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                                @csrf
                                @method('DELETE')

                                <button class="btn btn-danger btn-sm w-100">
                                    Batalkan
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary btn-sm w-100" disabled>-</button>
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>

        </table>
    </div>

    @endif
</div>
@endsection