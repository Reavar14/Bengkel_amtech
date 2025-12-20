@extends('layouts.frontend-layout')

@section('title', 'Register')

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">

        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h3 class="mb-3 text-center">Daftar Akun</h3>

                    {{-- ALERT ERROR GLOBAL --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Terjadi kesalahan:</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- FORM REGISTER REAL --}}
                    <form action="{{ route('register.store') }}" method="POST">
                        @csrf

                        {{-- NAMA --}}
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap</label>
                            <input name="name" 
                                   class="form-control @error('name') is-invalid @enderror"
                                   value="{{ old('name') }}"
                                   required>

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- EMAIL --}}
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input name="email" type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   value="{{ old('email') }}"
                                   required>

                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- PHONE --}}
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input name="phone"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   value="{{ old('phone') }}"
                                   required>

                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- PASSWORD --}}
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input name="password" type="password" 
                                   class="form-control @error('password') is-invalid @enderror"
                                   required>

                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- CONFIRM PASSWORD --}}
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input name="password_confirmation" type="password"
                                   class="form-control"
                                   required>
                        </div>

                        <button class="btn btn-primary w-100" type="submit">Daftar</button>

                    </form>

                    <hr>

                    <p class="text-center">
                        Sudah punya akun?
                        <a href="{{ route('login') }}">Login</a>
                    </p>

                </div>
            </div>
        </div>

    </div>
</div>

@endsection
