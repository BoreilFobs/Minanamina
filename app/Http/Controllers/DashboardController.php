<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignParticipation;
use App\Models\UserPiecesTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Redirect superadmin users to admin dashboard
        if ($user->role === 'superadmin') {
            return redirect()->route('admin.dashboard');
        }

        // Redirect campaign creators to admin campaigns page
        if ($user->role === 'campaign_creator') {
            return redirect()->route('admin.campaigns.index');
        }

        // Get user statistics - calculate from actual database records
        $actualReferralEarnings = $user->piecesTransactions()
            ->where('type', 'referral_bonus')
            ->where('amount', '>', 0)
            ->sum('amount');
        
        $stats = [
            'pieces_balance' => $user->pieces_balance,
            'total_campaigns' => $user->participations()->count(),
            'completed_campaigns' => $user->participations()->where('status', 'completed')->count(),
            'total_referrals' => $user->referredUsers()->count(),
            'referral_earnings' => $actualReferralEarnings,
            'active_campaigns' => $user->participations()->where('status', 'active')->count(),
        ];

        // Recent activities (last 10 transactions)
        $recentActivities = UserPiecesTransaction::where('user_id', $user->id)
            ->latest()
            ->take(10)
            ->get();

        // Recent participations
        $recentParticipations = CampaignParticipation::with('campaign')
            ->where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();

        // Available campaigns (published and not expired)
        $availableCampaigns = Campaign::where('status', 'published')
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->whereDoesntHave('participations', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->take(6)
            ->get();

        return view('dashboard', compact(
            'user',
            'stats',
            'recentActivities',
            'recentParticipations',
            'availableCampaigns'
        ));
    }
}
