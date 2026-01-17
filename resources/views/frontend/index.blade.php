@extends('layouts.frontend-layout')

@section('title', 'Beranda')

@section('content')

<!-- HERO -->
<header class="hero text-white text-center d-flex align-items-center">
    <div class="container">
        <h1 class="display-5 fw-bold">Servis Motor Profesional & Cepat</h1>
        <p class="lead mb-4">
            Perbaikan mesin, servis rutin, dan pelayanan berkualitas.
            Booking online mudah dan bebas antre.
        </p>

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
                    <img src="{{ asset('frontend/images/ringan.jpg') }}" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">Service Ringan</h5>
                        <p class="card-text">Ganti oli, tune-up, pengecekan rem.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ asset('frontend/images/berat.jpg') }}" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">Service Berat</h5>
                        <p class="card-text">
                            Perbaikan mesin, kopling, transmisi dan kelistrikan.
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card h-100 shadow-sm">
                    <img src="{{ asset('frontend/images/rem.jpeg') }}" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title">Service Kaki-kaki</h5>
                        <p class="card-text">
                            Pengecekan roda, suspensi, dan sistem pengereman.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ABOUT -->
<section id="about" class="bg-light py-5">
    <div class="container">
        <h2 class="mb-4 text-center">Tentang Kami</h2>

        <div class="row align-items-center g-4">
            <!-- Gambar -->
            <div class="col-md-6 text-center">
                <img src="{{ asset('frontend/images/logo.png') }}"
                     class="img-fluid rounded shadow about-logo"
                     alt="Bengkel Anugrah Manual Tech">
            </div>

            <!-- Teks -->
            <div class="col-md-6">
                <p>
                    Bengkel Motor Anugrah Manual Tech berdiri sejak 2011 di Jakarta Barat
                    dan menjadi bengkel terpercaya bagi masyarakat sekitar.
                </p>
                <p>
                    Dengan teknisi berpengalaman dan mengikuti teknologi otomotif terbaru,
                    kami berkomitmen memberikan layanan terbaik.
                </p>
                <p>
                    Kini tersedia sistem booking online untuk mengurangi antrean
                    dan mempermudah pelanggan.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- CTA -->
<section id="cta" class="text-white text-center py-5" style="background:#111;">
    <div class="container">
        <h3>Butuh servis cepat? Pesan sekarang</h3>

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
                <p><strong>Alamat:</strong> Jl. Pelita 7 Tomang, Jakarta Barat</p>
                <p><strong>Telepon:</strong> +62 818-0613-5119</p>
                <p><strong>Jam Buka:</strong> 09.00 - 19.00 WIB</p>
            </div>

            <div class="col-md-6">

                {{-- ALERT SUKSES --}}
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form id="contactForm"
                      action="{{ route('kontak.kirim') }}"
                      method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input class="form-control" name="name" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input class="form-control" name="email" type="email" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Pesan</label>
                        <textarea class="form-control"
                                  name="message"
                                  rows="3"
                                  required></textarea>
                    </div>

                    <button class="btn btn-outline-primary" type="submit">
                        Kirim Pesan
                    </button>
                </form>

            </div>
        </div>
    </div>
</section>

</main>
@endsection

@push('scripts')
<script>
document.getElementById('contactForm')?.addEventListener('submit', function () {
    setTimeout(() => this.reset(), 300);
});
</script>
@endpush