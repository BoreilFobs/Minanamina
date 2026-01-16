<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignParticipation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $campaignIds = Campaign::where('created_by', $user->id)->pluck('id');
        
        // Overall stats
        $stats = [
            'total_campaigns' => Campaign::where('created_by', $user->id)->count(),
            'active_campaigns' => Campaign::where('created_by', $user->id)->where('status', 'published')->count(),
            'total_participants' => CampaignParticipation::whereIn('campaign_id', $campaignIds)->count(),
            'completed_participations' => CampaignParticipation::whereIn('campaign_id', $campaignIds)->where('status', 'completed')->count(),
            'total_pieces_distributed' => CampaignParticipation::whereIn('campaign_id', $campaignIds)->where('status', 'completed')->sum('pieces_earned'),
            'avg_conversion_rate' => 0,
        ];
        
        if ($stats['total_participants'] > 0) {
            $stats['avg_conversion_rate'] = round(($stats['completed_participations'] / $stats['total_participants']) * 100, 2);
        }
        
        // Last 7 days participations
        $weeklyData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $weeklyData[] = [
                'date' => $date->format('d/m'),
                'participations' => CampaignParticipation::whereIn('campaign_id', $campaignIds)
                    ->whereDate('created_at', $date)
                    ->count(),
                'completed' => CampaignParticipation::whereIn('campaign_id', $campaignIds)
                    ->where('status', 'completed')
                    ->whereDate('completed_at', $date)
                    ->count(),
            ];
        }
        
        // Campaign performance
        $campaigns = Campaign::where('created_by', $user->id)
            ->withCount(['participations', 'participations as completed_count' => function($q) {
                $q->where('status', 'completed');
            }])
            ->latest()
            ->take(10)
            ->get();
        
        return view('creator.analytics', compact('stats', 'weeklyData', 'campaigns'));
    }
}
