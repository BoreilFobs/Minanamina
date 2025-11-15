<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserPiecesTransaction;
use App\Services\RewardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PiecesManagementController extends Controller
{
    protected $rewardService;

    public function __construct(RewardService $rewardService)
    {
        $this->rewardService = $rewardService;
    }

    /**
     * Display pieces management dashboard
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by suspicious
        if ($request->filter === 'suspicious') {
            $query->where('is_flagged_suspicious', true);
        }

        // Filter by high earners
        if ($request->filter === 'high_earners') {
            $query->orderByDesc('lifetime_earnings')->limit(100);
        }

        $users = $query->withCount('piecesTransactions')
            ->orderByDesc('pieces_balance')
            ->paginate(20);

        // Statistics
        $stats = [
            'total_pieces_distributed' => UserPiecesTransaction::where('amount', '>', 0)->sum('amount'),
            'total_users_with_balance' => User::where('pieces_balance', '>', 0)->count(),
            'total_suspicious_users' => User::where('is_flagged_suspicious', true)->count(),
            'average_balance' => User::avg('pieces_balance'),
        ];

        return view('admin.pieces.index', compact('users', 'stats'));
    }

    /**
     * Show user's transaction history
     */
    public function userTransactions(User $user)
    {
        $transactions = $user->piecesTransactions()
            ->with('campaign')
            ->latest()
            ->paginate(50);

        return view('admin.pieces.user-transactions', compact('user', 'transactions'));
    }

    /**
     * Show manual adjustment form
     */
    public function adjustmentForm(User $user)
    {
        return view('admin.pieces.adjustment', compact('user'));
    }

    /**
     * Process manual adjustment
     */
    public function processAdjustment(Request $request, User $user)
    {
        $request->validate([
            'amount' => 'required|numeric|not_in:0',
            'reason' => 'required|string|min:10|max:500',
        ]);

        try {
            $transaction = $this->rewardService->manualAdjustment(
                $user,
                $request->amount,
                $request->reason,
                Auth::user()
            );

            return redirect()->route('admin.pieces.user-transactions', $user)
                ->with('success', 'Ajustement effectué avec succès! Nouveau solde: ' . $user->fresh()->pieces_balance . ' pièces');
        
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Show transaction reversal form
     */
    public function reversalForm(UserPiecesTransaction $transaction)
    {
        return view('admin.pieces.reversal', compact('transaction'));
    }

    /**
     * Process transaction reversal
     */
    public function processReversal(Request $request, UserPiecesTransaction $transaction)
    {
        $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ]);

        try {
            $reversal = $this->rewardService->reverseTransaction(
                $transaction,
                $request->reason,
                Auth::user()
            );

            return redirect()->route('admin.pieces.user-transactions', $transaction->user)
                ->with('success', 'Transaction annulée avec succès!');
        
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Flag/unflag user as suspicious
     */
    public function toggleSuspicious(User $user)
    {
        $user->update([
            'is_flagged_suspicious' => !$user->is_flagged_suspicious,
        ]);

        $status = $user->is_flagged_suspicious ? 'signalé comme suspect' : 'marqué comme normal';
        
        return back()->with('success', "Utilisateur {$status}");
    }

    /**
     * Export transactions to CSV
     */
    public function export(Request $request)
    {
        $query = UserPiecesTransaction::with('user', 'campaign');

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->date_from) {
            $query->where('created_at', '>=', $request->date_from);
        }

        if ($request->date_to) {
            $query->where('created_at', '<=', $request->date_to);
        }

        $transactions = $query->get();

        $filename = 'pieces_transactions_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Headers
            fputcsv($file, [
                'ID',
                'Référence',
                'Utilisateur',
                'Téléphone',
                'Type',
                'Montant',
                'Solde Avant',
                'Solde Après',
                'Campagne',
                'Description',
                'Date',
            ]);

            // Data
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $transaction->reference_id,
                    $transaction->user->name ?? 'N/A',
                    $transaction->user->phone ?? 'N/A',
                    $transaction->type,
                    $transaction->amount,
                    $transaction->balance_before,
                    $transaction->balance_after,
                    $transaction->campaign->title ?? 'N/A',
                    $transaction->description ?? 'N/A',
                    $transaction->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
