<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignParticipation;
use App\Models\ConversionRequest;
use App\Models\User;
use App\Models\UserPiecesTransaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Users Stats
        $totalUsers = User::count();
        $newUsersToday = User::whereDate('created_at', Carbon::today())->count();
        $newUsersWeek = User::where('created_at', '>=', Carbon::now()->subWeek())->count();
        $activeUsers = User::where('last_completion_at', '>=', Carbon::now()->subDays(7))->count();

        // Campaigns Stats
        $totalCampaigns = Campaign::count();
        $activeCampaigns = Campaign::where('status', 'published')->count();
        $pendingApprovals = Campaign::where('status', 'pending_review')->count();
        
        // Participations Stats
        $totalParticipations = CampaignParticipation::count();
        $pendingValidations = CampaignParticipation::where('status', 'pending')->count();
        $completedToday = CampaignParticipation::where('status', 'completed')
            ->whereDate('completed_at', Carbon::today())
            ->count();

        // Conversions Stats
        $pendingConversions = ConversionRequest::where('status', 'pending')->count();
        $totalConversions = ConversionRequest::count();
        $totalPaidOut = ConversionRequest::where('status', 'completed')->sum('cash_amount');
        $pendingPayout = ConversionRequest::whereIn('status', ['pending', 'approved', 'processing'])->sum('cash_amount');

        // Pieces Stats
        $totalPiecesDistributed = UserPiecesTransaction::where('amount', '>', 0)->sum('amount');
        $piecesDistributedToday = UserPiecesTransaction::where('amount', '>', 0)
            ->whereDate('created_at', Carbon::today())
            ->sum('amount');

        // Recent Activity
        $recentParticipations = CampaignParticipation::with(['user', 'campaign'])
            ->latest()
            ->limit(5)
            ->get();

        $recentConversions = ConversionRequest::with('user')
            ->latest()
            ->limit(5)
            ->get();

        $recentUsers = User::latest()
            ->limit(5)
            ->get();

        // Campaigns needing attention
        $campaignsNeedingApproval = Campaign::where('status', 'pending_review')
            ->with('creator')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'newUsersToday',
            'newUsersWeek',
            'activeUsers',
            'totalCampaigns',
            'activeCampaigns',
            'pendingApprovals',
            'totalParticipations',
            'pendingValidations',
            'completedToday',
            'pendingConversions',
            'totalConversions',
            'totalPaidOut',
            'pendingPayout',
            'totalPiecesDistributed',
            'piecesDistributedToday',
            'recentParticipations',
            'recentConversions',
            'recentUsers',
            'campaignsNeedingApproval'
        ));
    }
}
