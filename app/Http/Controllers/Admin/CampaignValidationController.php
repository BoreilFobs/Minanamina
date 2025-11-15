<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CampaignParticipation;
use App\Services\RewardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignValidationController extends Controller
{
    protected $rewardService;

    public function __construct(RewardService $rewardService)
    {
        $this->rewardService = $rewardService;
    }

    /**
     * Display participations pending validation
     */
    public function index(Request $request)
    {
        $query = CampaignParticipation::with('user', 'campaign')
            ->where('status', 'pending');

        // Filter by campaign
        if ($request->campaign_id) {
            $query->where('campaign_id', $request->campaign_id);
        }

        // Search by user
        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $participations = $query->latest()->paginate(50);

        $stats = [
            'pending_count' => CampaignParticipation::where('status', 'pending')->count(),
            'validated_today' => CampaignParticipation::where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),
        ];

        return view('admin.validations.index', compact('participations', 'stats'));
    }

    /**
     * Validate (complete) a participation
     */
    public function validate(CampaignParticipation $participation)
    {
        if ($participation->status !== 'pending') {
            return back()->with('error', 'Seules les participations en attente peuvent être validées');
        }

        $result = $this->rewardService->awardCampaignCompletion($participation);

        if ($result['success']) {
            return back()->with('success', $result['message'] . ' - ' . $result['amount'] . ' pièces attribuées');
        } else {
            if (isset($result['flagged']) && $result['flagged']) {
                return back()->with('warning', $result['message']);
            }
            return back()->with('error', $result['message']);
        }
    }

    /**
     * Reject a participation
     */
    public function reject(Request $request, CampaignParticipation $participation)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        if ($participation->status !== 'pending') {
            return back()->with('error', 'Seules les participations en attente peuvent être rejetées');
        }

        $participation->update([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Participation rejetée');
    }

    /**
     * Bulk validate participations
     */
    public function bulkValidate(Request $request)
    {
        $request->validate([
            'participation_ids' => 'required|array',
            'participation_ids.*' => 'exists:campaign_participations,id',
        ]);

        $validated = 0;
        $failed = 0;
        $flagged = 0;

        foreach ($request->participation_ids as $id) {
            $participation = CampaignParticipation::find($id);
            
            if ($participation && $participation->status === 'pending') {
                $result = $this->rewardService->awardCampaignCompletion($participation);
                
                if ($result['success']) {
                    $validated++;
                } elseif (isset($result['flagged']) && $result['flagged']) {
                    $flagged++;
                } else {
                    $failed++;
                }
            }
        }

        $message = "Validation terminée: {$validated} validées, {$failed} échecs, {$flagged} signalées comme suspectes";
        
        return back()->with('success', $message);
    }
}
