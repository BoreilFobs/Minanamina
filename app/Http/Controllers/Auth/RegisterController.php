<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PhoneVerificationCode;
use App\Models\ReferralCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function showRegistrationForm(Request $request)
    {
        $referralCode = $request->get('ref');
        return view('auth.register', compact('referralCode'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20|unique:users|regex:/^\+?[0-9]{9,15}$/',
            'password' => 'required|string|min:8|confirmed',
            'referral_code' => 'nullable|string|exists:referral_codes,code',
        ]);

        // Create user with phone already verified
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'referral_code' => $this->generateUniqueReferralCode(),
            'phone_verified_at' => now(), // Auto-verify phone for now
            'status' => 'active',
            'pieces_balance' => 0,
        ]);

        // Create referral code for the user
        ReferralCode::create([
            'user_id' => $user->id,
            'code' => $user->referral_code,
            'is_active' => true,
        ]);

        // Handle referral if provided
        if ($request->referral_code) {
            $this->handleReferral($user, $request->referral_code);
        }

        // Log in the user immediately (phone verification disabled for now)
        auth()->login($user);

        return redirect()->route('dashboard')
            ->with('success', 'Inscription réussie! Bienvenue sur Minanamina.');
    }

    protected function generateUniqueReferralCode()
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (User::where('referral_code', $code)->exists());

        return $code;
    }

    protected function handleReferral($user, $referralCode)
    {
        $referralCodeRecord = ReferralCode::where('code', $referralCode)
            ->where('is_active', true)
            ->first();

        if ($referralCodeRecord) {
            $referrer = $referralCodeRecord->user;

            // Create referral relationship
            \App\Models\UserReferral::create([
                'referrer_id' => $referrer->id,
                'referral_user_id' => $user->id,
                'status' => 'active',
                'referral_level' => 1,
                'commission_percentage' => 10,
                'referred_at' => now(),
                'activated_at' => now(),
            ]);

            // Update referrer stats
            $referrer->increment('total_referrals');
            $referralCodeRecord->increment('total_referrals');

            // Award signup bonus to referrer (e.g., 50 pieces)
            $signupBonus = 50;
            $referrer->increment('pieces_balance', $signupBonus);
            $referrer->increment('referral_earnings', $signupBonus);

            \App\Models\UserPiecesTransaction::create([
                'user_id' => $referrer->id,
                'type' => 'referral_bonus',
                'amount' => $signupBonus,
                'balance_before' => $referrer->pieces_balance - $signupBonus,
                'balance_after' => $referrer->pieces_balance,
                'description' => "Bonus de parrainage pour {$user->name}",
                'reference_id' => 'REF-' . Str::random(10),
            ]);

            // Award signup bonus to new user (e.g., 25 pieces)
            $newUserBonus = 25;
            $user->increment('pieces_balance', $newUserBonus);

            \App\Models\UserPiecesTransaction::create([
                'user_id' => $user->id,
                'type' => 'referral_bonus',
                'amount' => $newUserBonus,
                'balance_before' => 0,
                'balance_after' => $newUserBonus,
                'description' => "Bonus de bienvenue pour l'inscription avec code de parrainage",
                'reference_id' => 'WELCOME-' . Str::random(10),
            ]);
        }
    }

    public function sendPhoneVerification($phone)
    {
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PhoneVerificationCode::create([
            'phone' => $phone,
            'code' => $code,
            'expires_at' => now()->addMinutes(15),
            'is_verified' => false,
        ]);

        // Send SMS using an SMS API (example with a generic HTTP API)
        $this->sendSMS($phone, "Votre code de vérification Minanamina est: {$code}. Valide pendant 15 minutes.");

        // Log for development
        Log::info("Code de vérification SMS pour {$phone}: {$code}");
    }

    protected function sendSMS($phone, $message)
    {
        try {
            // Example using a generic SMS API
            // Replace with your actual SMS provider (Twilio, Nexmo, African SMS providers, etc.)
            
            // Option 1: Twilio
            // $sid = env('TWILIO_SID');
            // $token = env('TWILIO_TOKEN');
            // $from = env('TWILIO_PHONE_NUMBER');
            // 
            // $client = new \Twilio\Rest\Client($sid, $token);
            // $client->messages->create($phone, [
            //     'from' => $from,
            //     'body' => $message
            // ]);

            // Option 2: Generic HTTP SMS API (example)
            $apiKey = env('SMS_API_KEY');
            $apiUrl = env('SMS_API_URL', 'https://api.smsgateway.com/send');
            
            if ($apiKey && $apiUrl) {
                Http::post($apiUrl, [
                    'api_key' => $apiKey,
                    'to' => $phone,
                    'message' => $message,
                    'sender' => 'Minanamina'
                ]);
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Erreur d'envoi SMS: " . $e->getMessage());
            return false;
        }
    }
}
