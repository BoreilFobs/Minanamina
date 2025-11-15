<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignParticipation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CampaignAnalyticsController extends Controller
{
    public function index(Campaign $campaign)
    {
        $campaign->load('creator', 'participations.user');

        // Overall Statistics
        $stats = [
            'total_participants' => $campaign->participations()->count(),
            'pending_participations' => $campaign->participations()->where('status', 'pending')->count(),
            'completed_participations' => $campaign->participations()->where('status', 'completed')->count(),
            'rejected_participations' => $campaign->participations()->where('status', 'rejected')->count(),
            'total_pieces_distributed' => $campaign->participations()->where('status', 'completed')->sum('pieces_earned'),
            'conversion_rate' => 0,
            'average_completion_time' => 0,
        ];

        if ($stats['total_participants'] > 0) {
            $stats['conversion_rate'] = round(($stats['completed_participations'] / $stats['total_participants']) * 100, 2);
        }

        // Calculate average completion time
        $completedParticipations = $campaign->participations()
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->get();

        if ($completedParticipations->count() > 0) {
            $totalMinutes = 0;
            foreach ($completedParticipations as $participation) {
                $totalMinutes += $participation->started_at->diffInMinutes($participation->completed_at);
            }
            $stats['average_completion_time'] = round($totalMinutes / $completedParticipations->count());
        }

        // Daily Participations (Last 30 days)
        $dailyData = CampaignParticipation::where('campaign_id', $campaign->id)
            ->where('started_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(started_at) as date, COUNT(*) as count, 
                         SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top Performers (users who completed)
        $topPerformers = CampaignParticipation::where('campaign_id', $campaign->id)
            ->where('status', 'completed')
            ->with('user')
            ->latest('completed_at')
            ->take(10)
            ->get();

        // Geographic Distribution
        $geographicData = CampaignParticipation::where('campaign_id', $campaign->id)
            ->join('users', 'campaign_participations.user_id', '=', 'users.id')
            ->selectRaw('users.country, COUNT(*) as count')
            ->whereNotNull('users.country')
            ->groupBy('users.country')
            ->orderByDesc('count')
            ->take(10)
            ->get();

        // Hourly Distribution
        $hourlyData = CampaignParticipation::where('campaign_id', $campaign->id)
            ->selectRaw('HOUR(started_at) as hour, COUNT(*) as count')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();

        return view('admin.campaigns.analytics.show', compact(
            'campaign', 
            'stats', 
            'dailyData', 
            'topPerformers', 
            'geographicData',
            'hourlyData'
        ));
    }

    public function export(Campaign $campaign, Request $request)
    {
        $format = $request->get('format', 'csv');

        $participations = CampaignParticipation::where('campaign_id', $campaign->id)
            ->with('user')
            ->get();

        if ($format === 'csv') {
            $filename = 'campaign_' . $campaign->id . '_analytics_' . now()->format('Y-m-d') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($participations) {
                $file = fopen('php://output', 'w');
                
                // Headers
                fputcsv($file, [
                    'ID',
                    'Utilisateur',
                    'Téléphone',
                    'Pays',
                    'Statut',
                    'Pièces Gagnées',
                    'Date de Participation',
                    'Date de Complétion',
                    'Temps de Complétion (min)'
                ]);

                // Data
                foreach ($participations as $participation) {
                    $completionTime = null;
                    if ($participation->status === 'completed' && $participation->completed_at) {
                        $completionTime = $participation->started_at->diffInMinutes($participation->completed_at);
                    }

                    fputcsv($file, [
                        $participation->id,
                        $participation->user->name ?? 'N/A',
                        $participation->user->phone ?? 'N/A',
                        $participation->user->country ?? 'N/A',
                        $participation->status,
                        $participation->pieces_earned ?? 0,
                        $participation->started_at->format('Y-m-d H:i:s'),
                        $participation->completed_at ? $participation->completed_at->format('Y-m-d H:i:s') : 'N/A',
                        $completionTime ?? 'N/A'
                    ]);
                }

                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return back()->with('error', 'Format non supporté');
    }
}
