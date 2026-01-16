<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignParticipation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $campaignIds = Campaign::where('created_by', $user->id)->pluck('id');
        
        $query = CampaignParticipation::whereIn('campaign_id', $campaignIds)
            ->with(['user', 'campaign'])
            ->latest();
        
        // Filter by campaign
        if ($request->campaign_id) {
            $query->where('campaign_id', $request->campaign_id);
        }
        
        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        // Search
        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }
        
        $participations = $query->paginate(20);
        $campaigns = Campaign::where('created_by', $user->id)->get();
        
        return view('creator.participations', compact('participations', 'campaigns'));
    }
    
    public function validate(CampaignParticipation $participation)
    {
        // Ensure the participation belongs to a campaign owned by the user
        $campaign = $participation->campaign;
        if ($campaign->created_by !== Auth::id()) {
            abort(403, 'Vous n\'avez pas accès à cette participation.');
        }
        
        if ($participation->status !== 'pending') {
            return back()->with('error', 'Cette participation a déjà été traitée.');
        }
        
        // Validate the participation
        $participation->update([
            'status' => 'completed',
            'completed_at' => now(),
            'pieces_earned' => $campaign->pieces_reward,
        ]);
        
        // Award pieces to the user
        $user = $participation->user;
        $user->increment('pieces_balance', $campaign->pieces_reward);
        
        // Create transaction record
        $user->piecesTransactions()->create([
            'amount' => $campaign->pieces_reward,
            'type' => 'campaign_reward',
            'description' => "Récompense pour la campagne: {$campaign->title}",
            'reference_type' => CampaignParticipation::class,
            'reference_id' => $participation->id,
        ]);
        
        return back()->with('success', 'Participation validée! L\'utilisateur a reçu ' . $campaign->pieces_reward . ' pièces.');
    }
    
    public function reject(Request $request, CampaignParticipation $participation)
    {
        // Ensure the participation belongs to a campaign owned by the user
        $campaign = $participation->campaign;
        if ($campaign->created_by !== Auth::id()) {
            abort(403, 'Vous n\'avez pas accès à cette participation.');
        }
        
        if ($participation->status !== 'pending') {
            return back()->with('error', 'Cette participation a déjà été traitée.');
        }
        
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500',
        ]);
        
        $participation->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'completed_at' => now(),
        ]);
        
        return back()->with('success', 'Participation rejetée.');
    }
}
