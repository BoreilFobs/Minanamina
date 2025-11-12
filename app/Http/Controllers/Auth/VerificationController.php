<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PhoneVerificationCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerificationController extends Controller
{
    public function show()
    {
        if (!session()->has('user_id_pending_verification')) {
            return redirect()->route('login');
        }

        $phone = session('verification_phone');

        return view('auth.verify', compact('phone'));
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
        ]);

        $userId = session('user_id_pending_verification');

        if (!$userId) {
            return back()->withErrors(['code' => 'Session expirée. Veuillez vous inscrire à nouveau.']);
        }

        $user = User::find($userId);

        if (!$user) {
            return back()->withErrors(['code' => 'Utilisateur non trouvé.']);
        }

        $verification = PhoneVerificationCode::where('phone', $user->phone)
            ->where('code', $request->code)
            ->where('expires_at', '>', now())
            ->where('is_verified', false)
            ->first();

        if ($verification) {
            $verification->update(['is_verified' => true]);
            $user->update(['phone_verified_at' => now()]);
            
            session()->forget(['user_id_pending_verification', 'verification_phone']);
            Auth::login($user);
            
            return redirect()->route('dashboard')->with('success', 'Compte vérifié avec succès!');
        }

        return back()->withErrors(['code' => 'Code de vérification invalide ou expiré.']);
    }

    public function resend(Request $request)
    {
        $userId = session('user_id_pending_verification');

        if (!$userId) {
            return back()->withErrors(['error' => 'Session expirée.']);
        }

        $user = User::find($userId);

        app(RegisterController::class)->sendPhoneVerification($user->phone);
        
        return back()->with('success', 'Code de vérification renvoyé sur votre téléphone.');
    }
}
