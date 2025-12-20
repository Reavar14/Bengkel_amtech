<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * REGISTER PROCESS
     */
    public function register(Request $request)
    {
        // Validasi register
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email',
            'phone'     => 'required|string|max:20',
            'password'  => 'required|min:6|confirmed',
        ]);

        // Simpan user baru
        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'],
            'password' => Hash::make($validated['password']),
        ]);

        // Role default user
        $user->assignRole('user');

        // Tidak auto login → agar user login manual
        return redirect()
            ->route('login')
            ->with('success', 'Registrasi berhasil! Silakan login.');
    }

    /**
     * LOGIN PROCESS
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return back()->withErrors([
                'email' => 'Email atau password salah.',
            ]);
        }

        $user = Auth::user();

        // Redirect berdasarkan role
        if ($user->hasRole('admin')) {
            return redirect()->route('filament.admin.pages.dashboard');
        }

        if ($user->hasRole('user')) {
            return redirect()->route('index')->with('success', 'Login berhasil!');
        }

        // Default fallback
        return redirect()->route('index');
    }

    /**
     * LOGOUT PROCESS
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('index');
    }

    /**
     * SHOW EDIT PROFILE PAGE
     */
    public function editProfile()
    {
        $user = auth()->user(); 
        return view('frontend.user-profile', compact('user'));
    }

    /**
     * UPDATE PROFILE PROCESS
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'phone'    => 'required|string|max:20',
            'password' => 'nullable|min:6|confirmed',
        ]);

        // Update field dasar
        $user->name  = $validated['name'];
        $user->email = $validated['email'];
        $user->phone = $validated['phone'];

        // Update password jika diisi
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}