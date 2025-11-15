<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\AdminAuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CampaignApprovalController extends Controller
{
    public function index()
    {
        $pendingCampaigns = Campaign::with('creator')
            ->where('status', 'pending_approval')
            ->latest()
            ->paginate(15);
        
        return view('admin.campaigns.approvals.index', compact('pendingCampaigns'));
    }

    public function approve(Request $request, Campaign $campaign)
    {
        if ($campaign->status !== 'pending_approval') {
            return back()->with('error', 'Seules les campagnes en attente peuvent être approuvées.');
        }

        DB::beginTransaction();
        try {
            $campaign->update([
                'status' => 'published',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            // Log audit
            $this->logAudit($campaign, 'approved', 'Campagne approuvée et publiée');

            // TODO: Send notification to campaign creator (Phase 6)

            DB::commit();

            return redirect()->route('admin.campaigns.approvals.index')
                ->with('success', 'Campagne approuvée et publiée avec succès!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de l\'approbation: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Campaign $campaign)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        if ($campaign->status !== 'pending_approval') {
            return back()->with('error', 'Seules les campagnes en attente peuvent être rejetées.');
        }

        DB::beginTransaction();
        try {
            $campaign->update([
                'status' => 'draft',
            ]);

            // Log audit with rejection reason
            $this->logAudit(
                $campaign, 
                'rejected', 
                'Campagne rejetée. Raison: ' . $request->rejection_reason
            );

            // TODO: Send notification to campaign creator with reason (Phase 6)

            DB::commit();

            return redirect()->route('admin.campaigns.approvals.index')
                ->with('success', 'Campagne rejetée. Le créateur a été notifié.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors du rejet: ' . $e->getMessage());
        }
    }

    public function requestModifications(Request $request, Campaign $campaign)
    {
        $request->validate([
            'modification_request' => 'required|string|min:10',
        ]);

        if ($campaign->status !== 'pending_approval') {
            return back()->with('error', 'Seules les campagnes en attente peuvent être modifiées.');
        }

        DB::beginTransaction();
        try {
            $campaign->update([
                'status' => 'draft',
            ]);

            // Log audit
            $this->logAudit(
                $campaign, 
                'modification_requested', 
                'Modifications demandées: ' . $request->modification_request
            );

            // TODO: Send notification to campaign creator (Phase 6)

            DB::commit();

            return redirect()->route('admin.campaigns.approvals.index')
                ->with('success', 'Demande de modifications envoyée au créateur.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur lors de la demande: ' . $e->getMessage());
        }
    }

    public function pause(Campaign $campaign)
    {
        if ($campaign->status !== 'published') {
            return back()->with('error', 'Seules les campagnes publiées peuvent être pausées.');
        }

        DB::beginTransaction();
        try {
            $campaign->update(['status' => 'paused']);

            $this->logAudit($campaign, 'paused', 'Campagne mise en pause');

            DB::commit();

            return back()->with('success', 'Campagne mise en pause avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    public function resume(Campaign $campaign)
    {
        if ($campaign->status !== 'paused') {
            return back()->with('error', 'Seules les campagnes pausées peuvent être relancées.');
        }

        DB::beginTransaction();
        try {
            $campaign->update(['status' => 'published']);

            $this->logAudit($campaign, 'resumed', 'Campagne relancée');

            DB::commit();

            return back()->with('success', 'Campagne relancée avec succès.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    private function logAudit(Campaign $campaign, string $action, string $details)
    {
        AdminAuditLog::create([
            'admin_id' => Auth::id(),
            'action' => $action,
            'entity_type' => 'Campaign',
            'entity_id' => $campaign->id,
            'details' => $details,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
