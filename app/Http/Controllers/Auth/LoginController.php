<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = [
            'phone' => $request->phone,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            // Phone verification check disabled for now
            // Will implement SMS verification later
            
            return redirect()->intended(route('dashboard'))
                ->with('success', 'Bienvenue, ' . Auth::user()->name . '!');
        }

        return back()->withErrors([
            'phone' => 'Les informations d\'identification fournies ne correspondent pas à nos enregistrements.',
        ])->withInput($request->only('phone'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Vous avez été déconnecté avec succès.');
    }
}
