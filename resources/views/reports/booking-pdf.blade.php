<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Booking</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background: #eee; }
    </style>
</head>
<body>

<h2>Laporan Booking</h2>

<table width="100%" border="1" cellspacing="0" cellpadding="6">
    <thead>
        <tr>
            <th>Kode Booking</th> {{-- 🔥 WAJIB --}}
            <th>Nama</th>
            <th>Mekanik</th>
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($bookings as $booking)
            <tr>
                <td>{{ $booking->booking_code }}</td> {{-- 🔥 INI KUNCI --}}
                <td>
                    {{ $booking->user->name ?? $booking->name ?? '-' }}
                </td>
                <td>
                    {{ $booking->mechanic->name ?? '-' }}
                </td>
                <td>{{ $booking->date }}</td>
                <td>{{ $booking->time }}</td>
                <td>{{ ucfirst($booking->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>