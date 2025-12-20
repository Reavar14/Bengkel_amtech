@extends('layouts.frontend-layout')

@section('title', 'Edit Profil')

@section('content')

<div class="container py-5">
    <h2>Edit Profil</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('user.profile.update') }}" method="POST" class="mt-4">
        @csrf

        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Nomor Telepon</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
        </div>

        <hr>

        <h5>Ubah Password</h5>
        <p class="text-muted">Kosongkan jika tidak ingin mengganti password.</p>

        <div class="mb-3">
            <label class="form-label">Password Baru</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <button class="btn btn-primary">Simpan Perubahan</button>
    </form>
</div>

@endsection