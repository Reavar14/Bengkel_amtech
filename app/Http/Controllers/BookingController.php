<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingCreatedMail;

class BookingController extends Controller
{
    /**
     * FORM BOOKING
     */
    public function create()
    {
        $mechanics = Mechanic::orderBy('name')->get();
        return view('frontend.booking', compact('mechanics'));
    }

    /**
     * DASHBOARD USER
     */
    public function dashboard()
    {
        $bookings = Booking::with('mechanic')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(5);

        return view('frontend.user-dashboard', compact('bookings'));
    }

    /**
     * SIMPAN BOOKING (FINAL – ANTI DOBEL SLOT)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'phone'       => 'required|string|max:20',
            'mechanic_id' => 'required|exists:mechanics,id',
            'date'        => 'required|date|after_or_equal:today',
            'time'        => 'required|string',
            'notes'       => 'nullable|string',
        ]);

        /**
         * SLOT RESMI
         */
        $allowedSlots = [
            '09.00-10.00',
            '11.00-12.00',
            '13.00-14.00',
            '15.00-16.00',
            '17.00-18.00',
        ];

        if (! in_array($validated['time'], $allowedSlots)) {
            return back()->withErrors([
                'time' => 'Slot waktu tidak valid.'
            ])->withInput();
        }

        /**
         * CEK JAM LEWAT (PAKAI JAM SELESAI SLOT)
         */
        $now = now('Asia/Jakarta');

        if ($validated['date'] === $now->toDateString()) {

            [, $end] = explode('-', $validated['time']);
            [$endH, $endM] = explode('.', $end);

            $slotEnd = Carbon::parse(
                $validated['date'],
                'Asia/Jakarta'
            )->setTime((int) $endH, (int) $endM);

            if ($now->greaterThanOrEqualTo($slotEnd)) {
                return back()->withErrors([
                    'time' => 'Slot waktu sudah lewat.'
                ])->withInput();
            }
        }

        /**
         * CEK TOTAL SLOT MEKANIK (MAX 5 / HARI)
         * status selesai DIHITUNG (PERMANEN LOCK)
         */
        $totalBooking = Booking::where('mechanic_id', $validated['mechanic_id'])
            ->where('date', $validated['date'])
            ->whereIn('status', ['menunggu', 'proses', 'selesai'])
            ->count();

        if ($totalBooking >= 5) {
            return back()->withErrors([
                'time' => 'Mekanik sudah penuh hari ini.'
            ])->withInput();
        }

        /**
         * CEK SLOT BENTROK (ANTI DOUBLE BOOKING)
         * status selesai DIKUNCI
         */
        $slotTaken = Booking::where('mechanic_id', $validated['mechanic_id'])
            ->where('date', $validated['date'])
            ->where('time', $validated['time'])
            ->whereIn('status', ['menunggu', 'proses', 'selesai'])
            ->exists();

        if ($slotTaken) {
            return back()->withErrors([
                'time' => 'Jam tersebut sudah dibooking mekanik ini.'
            ])->withInput();
        }

        /**
         * SIMPAN BOOKING
         */
        $booking = Booking::create([
            'user_id'     => auth()->id(),
            'name'        => $validated['name'],
            'phone'       => $validated['phone'],
            'email'       => auth()->user()->email,
            'mechanic_id' => $validated['mechanic_id'],
            'date'        => $validated['date'],
            'time'        => $validated['time'],
            'notes'       => $validated['notes'],
            'status'      => 'menunggu',
            'source'      => 'website',
        ]);

        Mail::to($booking->email)->send(
            new BookingCreatedMail($booking)
        );

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Booking berhasil dibuat.');
    }

    /**
     * BATALKAN BOOKING
     */
    public function cancel($id)
    {
        $booking = Booking::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if (in_array($booking->status, ['selesai', 'dibatalkan'])) {
            return back()->withErrors([
                'error' => 'Booking ini tidak bisa dibatalkan.'
            ]);
        }

        $booking->update([
            'status'       => 'dibatalkan',
            'release_time' => now('Asia/Jakarta'),
        ]);

        return redirect()
            ->route('user.dashboard')
            ->with('success', 'Booking berhasil dibatalkan.');
    }

    /**
     * AJAX SLOT TERPAKAI (FINAL)
     */
    public function availableSlots(Request $request)
    {
        $request->validate([
            'date'        => 'required|date',
            'mechanic_id' => 'required|exists:mechanics,id',
        ]);

        $booked = Booking::where('mechanic_id', $request->mechanic_id)
            ->where('date', $request->date)
            ->whereIn('status', ['menunggu', 'proses', 'selesai'])
            ->pluck('time')
            ->toArray();

        return response()->json([
            'booked' => $booked,
        ]);
    }
}