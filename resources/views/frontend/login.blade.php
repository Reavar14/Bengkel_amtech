@extends('layouts.frontend-layout')

@section('title', 'Login')

@section('content')

<div class="container py-5">
    <div class="row justify-content-center">

        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h3 class="mb-3 text-center">Login</h3>

                    {{-- TAMPILKAN ERROR VALIDATION --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- FORM LOGIN SESUNGGUHNYA --}}
                    <form action="{{ route('login.authenticate') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input name="email" type="email" class="form-control"
                                   value="{{ old('email') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input name="password" type="password" class="form-control" required>
                        </div>

                        <button class="btn btn-primary w-100" type="submit">Login</button>
                    </form>

                    <hr>

                    <p class="text-center">
                        Belum punya akun?
                        <a href="{{ route('register') }}">Daftar</a>
                    </p>

                </div>
            </div>
        </div>

    </div>
</div>

@endsection
