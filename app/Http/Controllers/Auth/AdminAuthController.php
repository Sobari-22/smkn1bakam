<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * Minimal login/logout for the Dashboard Admin, separate from whatever
 * Breeze/Fortify scaffold may already exist — kept intentionally simple
 * because the design calls for plain "Username" + "Password" (no
 * registration, password reset, or email verification flows needed for
 * an internal staff-only login).
 */
class AdminAuthController extends Controller
{
    public function show(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'], // field name kept as "email" to match the login form input
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // Accept login via username OR email in the same field, since staff
        // may be used to either — matches the single "Username" input in
        // the design without forcing a specific format.
        $attempt = Auth::attempt(
            ['username' => $credentials['email'], 'password' => $credentials['password']],
            $remember
        ) || Auth::attempt(
            ['email' => $credentials['email'], 'password' => $credentials['password']],
            $remember
        );

        if (! $attempt) {
            return back()
                ->withErrors(['email' => 'Username atau password yang Anda masukkan salah.'])
                ->onlyInput('email');
        }

        $request->session()->regenerate();

        // Selalu ke dashboard (boleh diakses semua role). Tidak memakai intended():
        // URL yang tersimpan bisa milik halaman yang tidak boleh dibuka role ini.
        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
