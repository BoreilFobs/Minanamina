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
            
            // Role-based redirect
            $user = Auth::user();
            $redirectRoute = $this->getRedirectRoute($user);
            
            return redirect()->intended($redirectRoute)
                ->with('success', 'Bienvenue, ' . $user->name . '!');
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

    /**
     * Determine redirect route based on user role
     */
    protected function getRedirectRoute($user): string
    {
        // Superadmin users - redirect to admin dashboard
        if ($user->role === 'superadmin') {
            return route('admin.dashboard');
        }
        
        // Campaign creators - redirect to admin campaigns page
        if ($user->role === 'campaign_creator') {
            return route('admin.campaigns.index');
        }
        
        // Regular user
        return route('dashboard');
    }
}
