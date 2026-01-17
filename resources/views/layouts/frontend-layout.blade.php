<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - Anugrah Manual Tech</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/png" href="{{ asset('frontend/images/logokecil1.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Custom Styles --}}
    <link href="{{ asset('frontend/styles.css') }}" rel="stylesheet">

    @stack('styles')
</head>
<body>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">

            {{-- LOGO + BRAND --}}
            <a class="navbar-brand d-flex align-items-center" href="{{ route('index') }}">
                <img
                    src="{{ asset('frontend/images/logokecil1.png') }}"
                    alt="Logo"
                    class="logo-navbar me-2"
                >
                <span class="brand-text">Anugrah Manual Tech</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain3">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navMain3">
                <ul class="navbar-nav ms-auto">

                    {{-- Jika halaman login/register → hanya tampilkan Login --}}
                    @if (request()->routeIs('login') || request()->routeIs('register'))

                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>

                    @else

                        {{-- MENU PUBLIC --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/#services') }}">Layanan</a>
                        </li>

                        {{-- MEKANIK diganti ABOUT US --}}
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/#about') }}">Tentang kami</a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/#contact') }}">Kontak</a>
                        </li>

                        {{-- USER LOGIN --}}
                        @auth

                            {{-- Book Now --}}
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('booking') }}">Booking</a>
                            </li>

                            {{-- PROFILE --}}
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center"
                                   href="#" id="navbarProfile" role="button"
                                   data-bs-toggle="dropdown" aria-expanded="false">

                                    <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff"
                                         class="rounded-circle me-2"
                                         width="32" height="32">

                                    {{ Auth::user()->name }}
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li class="dropdown-item-text">
                                        <strong>{{ Auth::user()->name }}</strong><br>
                                        <small>{{ Auth::user()->email }}</small>
                                    </li>

                                    <li><hr class="dropdown-divider"></li>

                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.dashboard') }}">
                                            Booking Saya
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.profile') }}">
                                            Edit Profil
                                        </a>
                                    </li>

                                    <li><hr class="dropdown-divider"></li>

                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button class="dropdown-item text-danger">Logout</button>
                                        </form>
                                    </li>

                                </ul>
                            </li>

                        @endauth

                        {{-- GUEST --}}
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Login</a>
                            </li>
                        @endguest

                    @endif

                </ul>
            </div>
        </div>
    </nav>


    {{-- MAIN CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container text-center">
            <small>&copy; {{ date('Y') }} Anugrah Manual Tech. All rights reserved.</small>
        </div>
    </footer>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')

</body>
</html>