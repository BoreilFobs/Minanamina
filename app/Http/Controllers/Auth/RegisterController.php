<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PhoneVerificationCode;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    protected $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }
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
            'referral_code' => 'nullable|string',
        ]);

        // Validate referral code if provided
        if ($request->referral_code && !$this->referralService->validateReferralCode($request->referral_code)) {
            return back()->withErrors(['referral_code' => 'Code de parrainage invalide'])->withInput();
        }

        // Create user with phone already verified
        $user = User::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'phone_verified_at' => now(),
            'status' => 'active',
            'pieces_balance' => 0,
            'role' => 'user',
        ]);

        // Generate referral code for new user
        $user->generateReferralCode();

        // Handle referral if provided
        if ($request->referral_code) {
            $result = $this->referralService->processReferral($user, $request->referral_code);
            
            if ($result['success']) {
                session()->flash('referral_success', $result['message']);
            }
        }

        // Log in the user immediately
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
