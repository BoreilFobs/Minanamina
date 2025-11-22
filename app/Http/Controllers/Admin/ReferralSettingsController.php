<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Referral;
use App\Models\ReferralSetting;
use App\Models\User;
use App\Services\ReferralService;
use Illuminate\Http\Request;

class ReferralSettingsController extends Controller
{
    protected $referralService;

    public function __construct(ReferralService $referralService)
    {
        $this->referralService = $referralService;
    }

    /**
     * Display referral settings and statistics
     */
    public function index()
    {
        $settings = [
            'referral_bonus_amount' => ReferralSetting::get('referral_bonus_amount', 500),
            'new_user_bonus_amount' => ReferralSetting::get('new_user_bonus_amount', 100),
            'referral_enabled' => ReferralSetting::get('referral_enabled', true),
        ];

        $stats = $this->referralService->getGlobalStats();

        $recentReferrals = Referral::with(['referrer', 'referred'])
            ->latest()
            ->limit(10)
            ->get();

        return view('admin.referrals.index', compact('settings', 'stats', 'recentReferrals'));
    }

    /**
     * Update referral bonus amount
     */
    public function updateBonus(Request $request)
    {
        $request->validate([
            'bonus_amount' => 'required|integer|min:0|max:10000',
        ]);

        $this->referralService->updateReferralBonusAmount($request->bonus_amount);

        return back()->with('success', "Le bonus de parrainage a été mis à jour à {$request->bonus_amount} pièces");
    }

    /**
     * Update new user bonus amount
     */
    public function updateNewUserBonus(Request $request)
    {
        $request->validate([
            'new_user_bonus_amount' => 'required|integer|min:0|max:10000',
        ]);

        $this->referralService->updateNewUserBonusAmount($request->new_user_bonus_amount);

        return back()->with('success', "Le bonus d'inscription a été mis à jour à {$request->new_user_bonus_amount} pièces");
    }

    /**
     * Toggle referral system
     */
    public function toggleSystem(Request $request)
    {
        $enabled = $request->boolean('enabled');
        
        $this->referralService->toggleSystem($enabled);

        $message = $enabled 
            ? 'Le système de parrainage a été activé' 
            : 'Le système de parrainage a été désactivé';

        return back()->with('success', $message);
    }

    /**
     * View all referrals
     */
    public function allReferrals(Request $request)
    {
        $query = Referral::with(['referrer', 'referred']);

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->search) {
            $query->whereHas('referrer', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            })->orWhereHas('referred', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $referrals = $query->latest()->paginate(50);

        return view('admin.referrals.all', compact('referrals'));
    }

    /**
     * View top referrers
     */
    public function topReferrers()
    {
        $topReferrers = User::where('total_referrals', '>', 0)
            ->with(['referredUsers' => function($query) {
                $query->select('id', 'referred_by', 'name', 'avatar', 'created_at');
            }])
            ->withCount('referredUsers')
            ->orderByDesc('total_referrals')
            ->paginate(25);

        return view('admin.referrals.top-referrers', compact('topReferrers'));
    }
}
