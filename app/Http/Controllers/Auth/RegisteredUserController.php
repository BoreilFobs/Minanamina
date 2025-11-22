<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    protected $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $referralCode = $request->get('ref');
        return view('auth.register', compact('referralCode'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20', 'unique:'.User::class, 'regex:/^\+?[0-9]{9,15}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'referral_code' => ['nullable', 'string'],
        ]);

        // Validate referral code if provided
        if ($request->referral_code && !$this->referralService->validateReferralCode($request->referral_code)) {
            return back()->withErrors(['referral_code' => 'Code de parrainage invalide'])->withInput();
        }

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

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
