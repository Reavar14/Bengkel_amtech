@extends('layouts.frontend-layout')

@section('title', 'Beranda')

@section('content')

<!-- HERO -->
<header class="hero text-white text-center d-flex align-items-center">
    <div class="container">
        <h1 class="display-5 fw-bold">Servis Motor Profesional & Cepat</h1>
        <p class="lead mb-4">Perbaikan mesin, servis rutin, dan pelayanan berkualitas. Booking online mudah dan bebas antre.</p>

        @guest
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                Buat Booking
            </a>
        @else
            <a href="{{ route('booking') }}" class="btn btn-primary btn-lg">
                Buat Booking
            </a>
        @endguest

    </div>
</header>

<main>

    <!-- SERVICES -->
    <section id="services" class="py-5">
        <div class="container">
            <h2 class="mb-4 text-center">Layanan Kami</h2>

            <div class="row g-4">

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('frontend/images/ringan.jpg') }}"
                             class="card-img-top" alt="service">
                        <div class="card-body">
                            <h5 class="card-title">Service Ringan</h5>
                            <p class="card-text">Ganti oli, tune-up, pengecekan rem.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('frontend/images/berat.jpg') }}"
                             class="card-img-top" alt="service">
                        <div class="card-body">
                            <h5 class="card-title">Service Berat</h5>
                            <p class="card-text">Perbaikan mesin, kopling, transmisi dan kelistrikan.</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('frontend/images/rem.jpeg') }}"
                             class="card-img-top" alt="service">
                        <div class="card-body">
                            <h5 class="card-title">Service Kaki-kaki Motor</h5>
                            <p class="card-text">Pengecekan roda, suspensi, dan sistem pengereman.</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- ABOUT US -->
    <section id="about" class="bg-light py-5">
        <div class="container">
            <h2 class="mb-4 text-center">Tentang Kami</h2>

            <div class="row align-items-center g-4">

                <div class="col-md-6">
                    <img src="{{ asset('frontend/images/logo.png') }}"
                         class="img-fluid rounded shadow" alt="Tentang Kami">
                </div>

                <div class="col-md-6">

                    <p>
                        Bengkel Motor Anugrah Manual Tech telah berdiri sejak tahun 2011 di Jakarta Barat dan terus
                        berkembang menjadi salah satu bengkel terpercaya bagi masyarakat sekitar.
                        Bengkel ini melayani berbagai jenis servis rutin maupun perbaikan kendaraan bermotor,
                        dengan jumlah pelanggan yang terus meningkat setiap tahunnya.
                    </p>

                    <p>
                        Dengan pengalaman lebih dari 13 tahun, tim teknisi kami terdiri dari tenaga profesional
                        yang selalu mengikuti perkembangan teknologi otomotif terbaru.
                        Kepercayaan pelanggan adalah prioritas kami, sehingga pelayanan yang jujur, cepat,
                        dan berkualitas selalu menjadi komitmen utama dalam setiap pengerjaan.
                    </p>

                    <p>
                        Untuk meningkatkan kenyamanan, kami menghadirkan sistem
                        booking servis online agar pelanggan dapat memilih jadwal,
                        mengurangi antrean, dan mencegah bentrok waktu servis.
                    </p>

                </div>

            </div>
        </div>
    </section>

    <!-- CTA -->
    <section id="cta" class="text-white text-center py-5" style="background:#111;">
        <div class="container">
            <h3>Butuh servis cepat? Pesan sekarang dan dapatkan pengecekan gratis</h3>

            @guest
                <a href="{{ route('login') }}" class="btn btn-primary mt-3">
                    Pesan Sekarang
                </a>
            @else
                <a href="{{ route('booking') }}" class="btn btn-primary mt-3">
                    Pesan Sekarang
                </a>
            @endguest

        </div>
    </section>

    <!-- CONTACT -->
    <section id="contact" class="py-5">
        <div class="container">
            <h2 class="mb-4 text-center">Kontak</h2>

            <div class="row">

                <div class="col-md-6">
                    <p><strong>Alamat:</strong> Jl. Pelita 7 Jl. Tomang Banjir Kanal No.7-8, DKI Jakarta 11430</p>
                    <p><strong>Telepon:</strong> +62 818-0613-5119</p>
                    <p><strong>Jam Buka:</strong> 09.00 - 19.00 WIB</p>
                </div>

                <div class="col-md-6">
                    <form id="contactForm">
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input class="form-control" id="cname" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input class="form-control" id="cemail" type="email" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Pesan</label>
                            <textarea class="form-control" id="cmessage" rows="3" required></textarea>
                        </div>

                        <button class="btn btn-outline-primary" type="submit">Kirim Pesan</button>
                    </form>
                </div>

            </div>

        </div>
    </section>

</main>

@endsection

@push('scripts')
<script>
document.getElementById('contactForm')?.addEventListener('submit', function (e) {
    e.preventDefault();
    alert('Terima kasih! Pesan Anda sudah terkirim (simulasi).');
    this.reset();
});
</script>
@endpush