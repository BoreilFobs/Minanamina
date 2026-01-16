<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignParticipation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user's campaigns
        $campaigns = Campaign::where('created_by', $user->id)
            ->latest()
            ->take(5)
            ->get();
        
        // Calculate statistics
        $totalCampaigns = Campaign::where('created_by', $user->id)->count();
        $activeCampaigns = Campaign::where('created_by', $user->id)
            ->where('status', 'published')
            ->count();
        $draftCampaigns = Campaign::where('created_by', $user->id)
            ->where('status', 'draft')
            ->count();
        $pendingApproval = Campaign::where('created_by', $user->id)
            ->whereIn('status', ['pending_approval', 'pending_review'])
            ->count();
        
        // Total participants and conversions
        $campaignIds = Campaign::where('created_by', $user->id)->pluck('id');
        $totalParticipants = CampaignParticipation::whereIn('campaign_id', $campaignIds)->count();
        $completedParticipations = CampaignParticipation::whereIn('campaign_id', $campaignIds)
            ->where('status', 'completed')
            ->count();
        $pendingParticipations = CampaignParticipation::whereIn('campaign_id', $campaignIds)
            ->where('status', 'pending')
            ->count();
        $totalPiecesDistributed = CampaignParticipation::whereIn('campaign_id', $campaignIds)
            ->where('status', 'completed')
            ->sum('pieces_earned');
        
        $stats = [
            'total_campaigns' => $totalCampaigns,
            'active_campaigns' => $activeCampaigns,
            'draft_campaigns' => $draftCampaigns,
            'pending_approval' => $pendingApproval,
            'total_participants' => $totalParticipants,
            'completed_participations' => $completedParticipations,
            'pending_participations' => $pendingParticipations,
            'total_pieces_distributed' => $totalPiecesDistributed,
        ];
        
        return view('creator.dashboard', compact('campaigns', 'stats'));
    }
}
