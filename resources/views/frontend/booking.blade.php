@extends('layouts.frontend-layout')

@section('title', 'Booking Servis')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-7">

            <h2>Form Reservasi Servis</h2>
            <p>Isi data untuk melakukan booking servis kendaraan Anda.</p>

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('booking.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input name="name" class="form-control" value="{{ old('name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nomor Telepon</label>
                    <input name="phone" class="form-control" value="{{ old('phone') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input class="form-control" value="{{ auth()->user()->email }}" readonly>
                </div>

                <div class="mb-3">
                    <label class="form-label">Pilih Mekanik</label>
                    <select name="mechanic_id" class="form-select" required>
                        <option value="">-- Pilih Mekanik --</option>
                        @foreach ($mechanics as $mechanic)
                            <option value="{{ $mechanic->id }}">{{ $mechanic->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Tanggal</label>
                        <input
                            type="date"
                            name="date"
                            class="form-control"
                            min="{{ now('Asia/Jakarta')->toDateString() }}"
                            value="{{ old('date') }}"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Jam</label>
                        <select name="time" class="form-select" required>
                            <option value="">-- Pilih Jam --</option>
                            <option value="09.00-10.00">09.00 - 10.00</option>
                            <option value="11.00-12.00">11.00 - 12.00</option>
                            <option value="13.00-14.00">13.00 - 14.00</option>
                            <option value="15.00-16.00">15.00 - 16.00</option>
                            <option value="17.00-18.00">17.00 - 18.00</option>
                        </select>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label">Keluhan / Catatan</label>
                    <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                </div>

                <button class="btn btn-primary mt-4">Kirim Reservasi</button>
            </form>

        </div>
    </div>
</div>

{{-- 🔥 FINAL REAL-TIME SLOT LOGIC (JAM SELESAI SLOT) --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dateInput = document.querySelector('input[name="date"]');
    const mechanicSelect = document.querySelector('select[name="mechanic_id"]');
    const timeSelect = document.querySelector('select[name="time"]');

    async function loadSlots() {
        const date = dateInput.value;
        const mechanicId = mechanicSelect.value;

        // RESET SLOT
        [...timeSelect.options].forEach(option => {
            if (!option.value) return;
            option.disabled = false;
            option.text = option.value.replace('-', ' - ');
        });

        if (!date || !mechanicId) return;

        const response = await fetch(
            `{{ route('booking.slots') }}?date=${date}&mechanic_id=${mechanicId}`
        );

        const data = await response.json();
        const booked = data.booked || [];

        const now = new Date();
        const today = now.toISOString().split('T')[0];
        const currentMinutes = now.getHours() * 60 + now.getMinutes();

        [...timeSelect.options].forEach(option => {
            if (!option.value) return;

            // ⏱ AMBIL JAM SELESAI SLOT
            const end = option.value.split('-')[1];
            const [h, m] = end.split('.');
            const slotEndMinutes = parseInt(h) * 60 + parseInt(m);

            // ❌ Slot lewat (SETELAH JAM SELESAI)
            if (date === today && currentMinutes >= slotEndMinutes) {
                option.disabled = true;
                option.text += ' (Lewat)';
            }

            // ❌ Slot penuh (mekanik)
            if (booked.includes(option.value)) {
                option.disabled = true;
                option.text += ' (Penuh)';
            }
        });
    }

    dateInput.addEventListener('change', loadSlots);
    mechanicSelect.addEventListener('change', loadSlots);
});
</script>
@endsection