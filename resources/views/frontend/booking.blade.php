@extends('layouts.frontend-layout')

@section('title', 'Booking Servis')

@section('content')

<div class="container py-5">
    <div class="row">

        <!-- FORM -->
        <div class="col-lg-7">
            <h2>Form Reservasi Servis</h2>
            <p>Isi data untuk melakukan booking servis kendaraan Anda.</p>

            <!-- FORM LARAVEL SESUNGGUHNYA -->
            <form action="{{ route('booking.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input name="name" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input name="phone" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pilih Mekanik</label>
                    <select name="mechanic_id" class="form-select" required>
                        <option value="">-- Pilih Mekanik --</option>

                        @foreach ($mechanics as $mechanic)
                            <option value="{{ $mechanic->id }}"
                                {{ old('mechanic_id') == $mechanic->id ? 'selected' : '' }}>
                                {{ $mechanic->name }}
                            </option>
                        @endforeach
                    </select>

                    @error('mechanic_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal</label>
                        <input name="date" type="date" class="form-control" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jam</label>
                        <input name="time" type="time" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3 mt-3">
                    <label class="form-label">Keluhan / Catatan</label>
                    <textarea name="notes" class="form-control" rows="3"></textarea>
                </div>

                <button class="btn btn-primary" type="submit">Kirim Reservasi</button>
            </form>
        </div>

    </div>
</div>

@endsection
