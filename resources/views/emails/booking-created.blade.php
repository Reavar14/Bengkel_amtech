<h2>Booking Berhasil Dibuat</h2>

<p>Halo {{ $booking->name }},</p>

<p>Booking Anda berhasil dibuat dengan rincian sebagai berikut:</p>

<ul>
    <li><strong>Tanggal:</strong> {{ $booking->date }}</li>
    <li><strong>Jam:</strong> {{ $booking->time }}</li>
    <li><strong>Keluhan:</strong> {{ $booking->notes ?? '-' }}</li>
</ul>

<p>Kami akan menghubungi Anda apabila ada informasi terbaru.</p>

<p>Terima kasih,<br>
<strong>Anugrah Manual Tech</strong></p>