<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Booking</title>
</head>
<body style="font-family: Arial, sans-serif">

<h2>Konfirmasi Booking Servis</h2>

<p>Halo <strong>{{ $booking->name }}</strong>,</p>

<p>Booking servis Anda telah berhasil dibuat dengan detail berikut:</p>

<table cellpadding="6">
    <tr>
        <td><strong>Kode Booking</strong></td>
        <td>: {{ $booking->booking_code }}</td>
    </tr>
    <tr>
        <td><strong>Mekanik</strong></td>
        <td>: {{ $booking->mechanic->name ?? '-' }}</td>
    </tr>
    <tr>
        <td><strong>Tanggal</strong></td>
        <td>: {{ \Carbon\Carbon::parse($booking->date)->format('d M Y') }}</td>
    </tr>
    <tr>
        <td><strong>Jam</strong></td>
        <td>: {{ $booking->time }}</td>
    </tr>
    <tr>
        <td><strong>Status</strong></td>
        <td>: {{ ucfirst($booking->status) }}</td>
    </tr>
</table>

<p>Terima kasih telah melakukan reservasi di <strong>Anugrah Manual Tech</strong>.</p>

<p>Salam,<br>
<strong>Anugrah Manual Tech</strong></p>

</body>
</html>