<?php

namespace App\Http\Controllers;

use App\Services\ReferralService;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    protected $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    /**
     * Display user's referral dashboard
     */
    public function index()
    {
        $user = auth()->user();

        // Generate referral code if user doesn't have one
        if (!$user->hasReferralCode()) {
            $user->generateReferralCode();
            $user->refresh();
        }

        $stats = $this->referralService->getUserReferralStats($user);
        $bonusAmount = $this->referralService->getReferralBonusAmount();
        $referralLink = route('register', ['ref' => $user->referral_code]);

        return view('referrals.index', compact('user', 'stats', 'bonusAmount', 'referralLink'));
    }
}
