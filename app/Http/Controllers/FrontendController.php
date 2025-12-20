<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FrontendController extends Controller
{
    /**
     * Halaman Beranda
     */
    public function index()
    {
        return view('frontend.index');
    }

    /**
     * Halaman Booking (Form)
     */
    public function booking()
    {
        return view('frontend.booking');
    }

    /**
     * Halaman Login
     */
    public function login()
    {
        return view('frontend.login');
    }

    public function register()
    {
        return view('frontend.register');
    }

    /**
     * (Opsional) Proses Booking
     */
    public function submitBooking(Request $request)
    {
        // Nanti logic booking ke database ditambahkan di sini
        return back()->with('success', 'Reservasi berhasil dibuat!');
    }

    /**
     * (Opsional) Proses Login User
     */
    public function submitLogin(Request $request)
    {
        // Nanti logic login beneran ada di sini
        return redirect()->route('index');
    }
}
