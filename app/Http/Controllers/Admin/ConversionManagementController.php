<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ConversionRequest;
use App\Services\BadgeService;
use App\Services\RewardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ConversionManagementController extends Controller
{
    protected $rewardService;
    protected $badgeService;

    public function __construct(RewardService $rewardService, BadgeService $badgeService)
    {
        $this->rewardService = $rewardService;
        $this->badgeService = $badgeService;
    }

    /**
     * Display conversion requests
     */
    public function index(Request $request)
    {
        $query = ConversionRequest::with('user');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        } else {
            // Default to pending and processing
            $query->whereIn('status', ['pending', 'processing']);
        }

        $conversions = $query->latest()->paginate(20);

        // Statistics
        $stats = [
            'pending_count' => ConversionRequest::where('status', 'pending')->count(),
            'pending_amount' => ConversionRequest::where('status', 'pending')->sum('cash_amount'),
            'processing_count' => ConversionRequest::where('status', 'processing')->count(),
            'completed_today' => ConversionRequest::where('status', 'completed')
                ->whereDate('completed_at', today())
                ->count(),
            'total_paid_out' => ConversionRequest::where('status', 'completed')->sum('cash_amount'),
        ];

        return view('admin.conversions.index', compact('conversions', 'stats'));
    }

    /**
     * Show conversion request details
     */
    public function show(ConversionRequest $conversion)
    {
        $conversion->load('user', 'approver');
        return view('admin.conversions.show', compact('conversion'));
    }

    /**
     * Approve conversion request
     */
    public function approve(ConversionRequest $conversion)
    {
        if (!$conversion->isPending()) {
            return back()->with('error', 'Seules les demandes en attente peuvent être approuvées');
        }

        $conversion->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        // TODO: Trigger payment processing notification
        
        return back()->with('success', 'Demande approuvée! En attente de traitement du paiement.');
    }

    /**
     * Reject conversion request
     */
    public function reject(Request $request, ConversionRequest $conversion)
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10',
        ]);

        if (!$conversion->canBeProcessed()) {
            return back()->with('error', 'Cette demande ne peut pas être rejetée');
        }

        DB::beginTransaction();
        try {
            // Return pieces to user
            $user = $conversion->user;
            $user->addPieces(
                $conversion->pieces_amount,
                'reversal',
                null,
                "Remboursement: Conversion #{$conversion->id} rejetée"
            );

            $conversion->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);

            DB::commit();

            return back()->with('success', 'Demande rejetée et pièces remboursées');
        
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Mark as processing (payment initiated)
     */
    public function markProcessing(ConversionRequest $conversion)
    {
        if (!$conversion->isApproved()) {
            return back()->with('error', 'La demande doit être approuvée d\'abord');
        }

        $conversion->update([
            'status' => 'processing',
            'processed_at' => now(),
        ]);

        return back()->with('success', 'Marquée comme en cours de traitement');
    }

    /**
     * Mark as completed (payment sent)
     */
    public function markCompleted(Request $request, ConversionRequest $conversion)
    {
        $request->validate([
            'transaction_reference' => 'required|string',
            'payment_proof' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if (!in_array($conversion->status, ['approved', 'processing'])) {
            return back()->with('error', 'Statut invalide pour cette action');
        }

        $data = [
            'status' => 'completed',
            'completed_at' => now(),
            'transaction_reference' => $request->transaction_reference,
        ];

        // Handle payment proof upload
        if ($request->hasFile('payment_proof')) {
            $path = $request->file('payment_proof')->store('payment_proofs', 'public');
            $data['payment_proof'] = $path;
        }

        $conversion->update($data);

        // Check and award badges for conversions
        $this->badgeService->checkAndAwardBadges($conversion->user);

        return back()->with('success', 'Paiement marqué comme complété!');
    }

    /**
     * Add admin notes
     */
    public function addNotes(Request $request, ConversionRequest $conversion)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:1000',
        ]);

        $conversion->update([
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Notes ajoutées');
    }

    /**
     * Export conversion requests
     */
    public function export(Request $request)
    {
        $query = ConversionRequest::with('user');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->date_from) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $conversions = $query->get();

        $filename = 'conversions_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($conversions) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'ID',
                'Utilisateur',
                'Téléphone',
                'Pièces',
                'Montant Cash',
                'Taux',
                'Méthode de Paiement',
                'Statut',
                'Date Demande',
                'Date Complétion',
                'Référence',
            ]);

            foreach ($conversions as $conversion) {
                fputcsv($file, [
                    $conversion->id,
                    $conversion->user->name ?? 'N/A',
                    $conversion->user->phone ?? 'N/A',
                    $conversion->pieces_amount,
                    $conversion->cash_amount,
                    $conversion->conversion_rate,
                    $conversion->payment_method ?? 'N/A',
                    $conversion->status,
                    $conversion->created_at->format('Y-m-d H:i:s'),
                    $conversion->completed_at?->format('Y-m-d H:i:s') ?? 'N/A',
                    $conversion->transaction_reference ?? 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
