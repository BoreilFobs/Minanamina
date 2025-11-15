<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function index()
    {
        $query = Campaign::with('creator')->latest();
        
        // Filter by status if provided
        if (request('status')) {
            $query->where('status', request('status'));
        }
        
        // Search functionality
        if (request('search')) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . request('search') . '%')
                  ->orWhere('description', 'like', '%' . request('search') . '%');
            });
        }
        
        $campaigns = $query->paginate(15);
        
        return view('admin.campaigns.index', compact('campaigns'));
    }

    public function create()
    {
        return view('admin.campaigns.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'cpa_link' => 'required|url',
            'pieces_reward' => 'required|numeric|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'validation_rules' => 'nullable|string',
            'geographic_restrictions' => 'nullable|string',
        ]);

        $data = $request->except('image');
        $data['created_by'] = Auth::id();
        $data['status'] = 'draft';

        // Handle image upload
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('campaigns', 'public');
            $data['image'] = $imagePath;
        }

        // Parse geographic restrictions
        if ($request->geographic_restrictions) {
            $data['geographic_restrictions'] = json_encode(explode(',', $request->geographic_restrictions));
        }

        $campaign = Campaign::create($data);

        return redirect()->route('admin.campaigns.show', $campaign)
            ->with('success', 'Campagne créée avec succès! Statut: Brouillon');
    }

    public function show(Campaign $campaign)
    {
        $campaign->load('creator', 'participations.user');
        
        // Calculate statistics
        $stats = [
            'total_participants' => $campaign->participations()->count(),
            'completed_participations' => $campaign->participations()->where('status', 'completed')->count(),
            'total_pieces_distributed' => $campaign->participations()->where('status', 'completed')->sum('pieces_earned'),
            'conversion_rate' => 0,
        ];
        
        if ($stats['total_participants'] > 0) {
            $stats['conversion_rate'] = round(($stats['completed_participations'] / $stats['total_participants']) * 100, 2);
        }
        
        return view('admin.campaigns.show', compact('campaign', 'stats'));
    }

    public function edit(Campaign $campaign)
    {
        return view('admin.campaigns.edit', compact('campaign'));
    }

    public function update(Request $request, Campaign $campaign)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'cpa_link' => 'required|url',
            'pieces_reward' => 'required|numeric|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'validation_rules' => 'nullable|string',
            'geographic_restrictions' => 'nullable|string',
        ]);

        $data = $request->except('image');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($campaign->image) {
                Storage::disk('public')->delete($campaign->image);
            }
            $imagePath = $request->file('image')->store('campaigns', 'public');
            $data['image'] = $imagePath;
        }

        // Parse geographic restrictions
        if ($request->geographic_restrictions) {
            $data['geographic_restrictions'] = json_encode(explode(',', $request->geographic_restrictions));
        }

        $campaign->update($data);

        return redirect()->route('admin.campaigns.show', $campaign)
            ->with('success', 'Campagne mise à jour avec succès!');
    }

    public function destroy(Campaign $campaign)
    {
        // Soft delete
        if ($campaign->participations()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer une campagne avec des participations actives.');
        }

        // Delete image if exists
        if ($campaign->image) {
            Storage::disk('public')->delete($campaign->image);
        }

        $campaign->delete();

        return redirect()->route('admin.campaigns.index')
            ->with('success', 'Campagne supprimée avec succès!');
    }

    public function submitForApproval(Campaign $campaign)
    {
        if ($campaign->status !== 'draft') {
            return back()->with('error', 'Seules les campagnes en brouillon peuvent être soumises pour approbation.');
        }

        $campaign->update(['status' => 'pending_approval']);

        return back()->with('success', 'Campagne soumise pour approbation!');
    }

    public function duplicate(Campaign $campaign)
    {
        $newCampaign = $campaign->replicate();
        $newCampaign->title = $campaign->title . ' (Copie)';
        $newCampaign->status = 'draft';
        $newCampaign->created_by = Auth::id();
        $newCampaign->save();

        return redirect()->route('admin.campaigns.edit', $newCampaign)
            ->with('success', 'Campagne dupliquée avec succès!');
    }
}
