<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingCreatedMail;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function create()
    {
        $mechanics = Mechanic::orderBy('name')->get();
        return view('frontend.booking', compact('mechanics'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'phone'       => 'required|string|max:20',
            'mechanic_id' => 'required|exists:mechanics,id',
            'date'        => 'required|date',
            'time'        => 'required',
            'notes'       => 'nullable|string',
        ]);

        // ==========================
        // 1) Cegah Tanggal Masa Lalu
        // ==========================
        $selectedDate = Carbon::parse($validated['date'])->startOfDay();
        $today = Carbon::today();

        if ($selectedDate->lt($today)) {
            return back()->withErrors([
                'date' => 'Tanggal sudah lewat, pilih tanggal hari ini atau setelahnya.',
            ])->withInput();
        }

        // ==========================
        // 2) Jika hari ini -> Cegah Jam Masa Lalu
        // ==========================
        $selectedDateTime = Carbon::parse($validated['date'].' '.$validated['time']);
        $now = Carbon::now();

        if ($selectedDate->eq($today) && $selectedDateTime->lt($now)) {
            return back()->withErrors([
                'time' => 'Jam yang dipilih sudah lewat.',
            ])->withInput();
        }

        // =====================================================
        // 3) Cegah double booking berdasarkan status != done
        // =====================================================
        $exists = Booking::where('mechanic_id', $validated['mechanic_id'])
            ->where('date', $validated['date'])
            ->where('time', $validated['time'])
            ->where('status', '!=', 'done') // <--- slot masih kepake kalau status belum done
            ->exists();

        if ($exists) {
            return back()->withErrors([
                'time' => 'Slot ini masih terpakai, mekanik belum selesai pada jam tersebut.',
            ])->withInput();
        }

        // ==========================
        // 4) Create Booking
        // ==========================
        $booking = Booking::create([
            'user_id'     => auth()->id(),
            'name'        => $validated['name'],
            'phone'       => $validated['phone'],
            'email'       => auth()->user()->email,
            'mechanic_id' => $validated['mechanic_id'],
            'date'        => $validated['date'],
            'time'        => $validated['time'],
            'notes'       => $validated['notes'] ?? null,
            'status'      => 'pending',
        ]);

        // Kirim email
        Mail::to($booking->email)->send(new BookingCreatedMail($booking));

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Booking berhasil dibuat!');
    }

    public function cancel($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $booking->delete();

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Booking berhasil dibatalkan.');
    }

    public function dashboard()
    {
        $bookings = Booking::with('mechanic')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('frontend.user-dashboard', compact('bookings'));
    }
}