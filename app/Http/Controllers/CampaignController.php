<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignParticipation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CampaignController extends Controller
{
    public function index(Request $request)
    {
        $query = Campaign::where('status', 'published')
            ->where('end_date', '>=', now()); // Show active and upcoming campaigns

        // Search
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by reward range
        if ($request->filled('min_reward')) {
            $query->where('pieces_reward', '>=', $request->min_reward);
        }
        if ($request->filled('max_reward')) {
            $query->where('pieces_reward', '<=', $request->max_reward);
        }

        // Filter by geographic restrictions
        if (Auth::check() && Auth::user()->country) {
            // Show campaigns with no restrictions or campaigns available in user's country
            $query->where(function($q) {
                $q->whereNull('geographic_restrictions')
                  ->orWhereJsonContains('geographic_restrictions', Auth::user()->country);
            });
        }

        // Sorting
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'reward_high':
                $query->orderBy('pieces_reward', 'desc');
                break;
            case 'reward_low':
                $query->orderBy('pieces_reward', 'asc');
                break;
            case 'ending_soon':
                $query->orderBy('end_date', 'asc');
                break;
            default:
                $query->latest();
        }

        $campaigns = $query->paginate(12);

        return view('campaigns.index', compact('campaigns'));
    }

    public function show(Campaign $campaign)
    {
        if ($campaign->status !== 'published') {
            abort(404, 'Cette campagne n\'est pas disponible.');
        }

        $campaign->load('creator');

        $userParticipation = null;
        if (Auth::check()) {
            $userParticipation = CampaignParticipation::where('campaign_id', $campaign->id)
                ->where('user_id', Auth::id())
                ->first();
        }

        $stats = [
            'total_participants' => $campaign->participations()->count(),
            'completed_participations' => $campaign->participations()->where('status', 'completed')->count(),
        ];

        return view('campaigns.show', compact('campaign', 'userParticipation', 'stats'));
    }

    public function participate(Campaign $campaign)
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Vous devez être connecté pour participer.');
        }

        if ($campaign->status !== 'published') {
            return back()->with('error', 'Cette campagne n\'est pas disponible.');
        }

        // Check if campaign is still active
        if ($campaign->end_date < now()) {
            return back()->with('error', 'Cette campagne est terminée.');
        }

        if ($campaign->start_date > now()) {
            return back()->with('error', 'Cette campagne n\'a pas encore commencé.');
        }

        // Check if user already participated
        $existingParticipation = CampaignParticipation::where('campaign_id', $campaign->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($existingParticipation) {
            return back()->with('error', 'Vous participez déjà à cette campagne.');
        }

        // Check geographic restrictions
        if ($campaign->geographic_restrictions) {
            $restrictions = is_array($campaign->geographic_restrictions) 
                ? $campaign->geographic_restrictions
                : json_decode($campaign->geographic_restrictions, true);
            
            if (!empty($restrictions) && Auth::user()->country && !in_array(Auth::user()->country, $restrictions)) {
                return back()->with('error', 'Cette campagne n\'est pas disponible dans votre pays.');
            }
        }

        DB::beginTransaction();
        try {
            // Create participation
            CampaignParticipation::create([
                'campaign_id' => $campaign->id,
                'user_id' => Auth::id(),
                'status' => 'pending',
                'started_at' => now(),
            ]);

            // Increment current participants
            $campaign->increment('current_participants');

            DB::commit();

            return redirect()->away($campaign->cpa_link)
                ->with('success', 'Participation enregistrée! Complétez l\'action pour gagner vos pièces.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la participation: ' . $e->getMessage());
        }
    }

    public function myParticipations()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $participations = CampaignParticipation::with('campaign')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(15);

        $stats = [
            'total' => CampaignParticipation::where('user_id', Auth::id())->count(),
            'pending' => CampaignParticipation::where('user_id', Auth::id())->where('status', 'pending')->count(),
            'completed' => CampaignParticipation::where('user_id', Auth::id())->where('status', 'completed')->count(),
            'total_earned' => CampaignParticipation::where('user_id', Auth::id())->where('status', 'completed')->sum('pieces_earned'),
        ];

        return view('campaigns.participations', compact('participations', 'stats'));
    }
}
