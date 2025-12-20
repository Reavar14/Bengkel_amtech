<h2>Status Booking Diperbarui</h2>

<p>Halo {{ $booking->name }},</p>

<p>Status booking Anda telah diperbarui:</p>

<ul>
    <li><strong>Status:</strong> {{ ucfirst($booking->status) }}</li>
    <li><strong>Mekanik:</strong> {{ $booking->mechanic ?? '-' }}</li>
    <li><strong>Estimasi Selesai:</strong> {{ $booking->estimation ?? '-' }}</li>
    <li><strong>Catatan Mekanik:</strong> {{ $booking->mechanic_notes ?? '-' }}</li>
</ul>

<p>Terima kasih telah menggunakan layanan kami.</p>
<p><strong>Anugrah Manual Tech</strong></p>