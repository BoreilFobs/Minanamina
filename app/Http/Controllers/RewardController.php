<?php

namespace App\Http\Controllers;

use App\Models\ConversionRequest;
use App\Services\BadgeService;
use App\Services\RewardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RewardController extends Controller
{
    protected $rewardService;
    protected $badgeService;

    public function __construct(RewardService $rewardService, BadgeService $badgeService)
    {
        $this->rewardService = $rewardService;
        $this->badgeService = $badgeService;
    }

    /**
     * Display user's pieces dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        $transactions = $user->piecesTransactions()
            ->with('campaign')
            ->latest()
            ->paginate(20);

        $stats = [
            'current_balance' => $user->pieces_balance,
            'lifetime_earnings' => $user->lifetime_earnings,
            'total_campaigns_completed' => $user->total_campaigns_completed,
            'consecutive_completions' => $user->consecutive_completions,
            'referral_earnings' => $user->referral_earnings,
            'total_earned' => $user->piecesTransactions()->where('amount', '>', 0)->sum('amount'),
            'total_converted' => $user->piecesTransactions()->where('type', 'converted')->sum('amount'),
        ];

        $conversionRate = $this->rewardService->getConversionRate();
        $minimumConversion = $this->rewardService->getMinimumConversionAmount();
        $cashEquivalent = $this->rewardService->calculateCashAmount($user->pieces_balance);

        // Get badge data
        $badgesWithProgress = $this->badgeService->getUserBadgesWithProgress($user);
        $badgeStats = $this->badgeService->getUserBadgeStats($user);
        $recentBadges = $this->badgeService->getRecentBadges($user, 3);

        return view('rewards.index', compact(
            'user', 
            'transactions', 
            'stats', 
            'conversionRate', 
            'minimumConversion', 
            'cashEquivalent',
            'badgesWithProgress',
            'badgeStats',
            'recentBadges'
        ));
    }

    /**
     * Show conversion form
     */
    public function conversionForm()
    {
        $user = Auth::user();
        $conversionRate = $this->rewardService->getConversionRate();
        $minimumConversion = $this->rewardService->getMinimumConversionAmount();

        return view('rewards.convert', compact('user', 'conversionRate', 'minimumConversion'));
    }

    /**
     * Submit conversion request
     */
    public function submitConversion(Request $request)
    {
        $request->validate([
            'pieces_amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:orange_money,mtn_mobile_money,wave,bank_transfer,paypal',
            'payment_phone' => 'required_if:payment_method,orange_money,mtn_mobile_money,wave',
            'payment_email' => 'required_if:payment_method,paypal',
            'payment_account' => 'required_if:payment_method,bank_transfer',
        ]);

        $user = Auth::user();
        $pieces = $request->pieces_amount;

        // Validate conversion eligibility
        $canConvert = $this->rewardService->canConvert($user, $pieces);
        if (!$canConvert['can_convert']) {
            return back()
                ->withInput()
                ->with('error', $canConvert['reason']);
        }

        DB::beginTransaction();
        try {
            // Calculate cash amount
            $cashAmount = $this->rewardService->calculateCashAmount($pieces);
            $conversionRate = $this->rewardService->getConversionRate();

            // Deduct pieces from user
            $user->deductPieces($pieces, 'converted', "Demande de conversion en cash");

            // Create conversion request
            $conversion = ConversionRequest::create([
                'user_id' => $user->id,
                'pieces_amount' => $pieces,
                'cash_amount' => $cashAmount,
                'conversion_rate' => $conversionRate,
                'status' => 'pending',
                'payment_method' => $request->payment_method,
                'payment_phone' => $request->payment_phone,
                'payment_email' => $request->payment_email,
                'payment_account' => $request->payment_account,
                'payment_details' => json_encode($request->only(['payment_phone', 'payment_email', 'payment_account'])),
            ]);

            DB::commit();

            return redirect()->route('rewards.conversions')
                ->with('success', "Demande de conversion soumise avec succès! Montant: {$cashAmount} CFA. Vous serez notifié une fois traitée.");
        
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erreur: ' . $e->getMessage());
        }
    }

    /**
     * Display user's conversion requests
     */
    public function conversions()
    {
        $user = Auth::user();
        
        $conversions = $user->conversionRequests()
            ->latest()
            ->paginate(10);

        $stats = [
            'total_requests' => $user->conversionRequests()->count(),
            'pending_requests' => $user->conversionRequests()->where('status', 'pending')->count(),
            'completed_requests' => $user->conversionRequests()->where('status', 'completed')->count(),
            'total_converted_cash' => $user->conversionRequests()->where('status', 'completed')->sum('cash_amount'),
        ];

        return view('rewards.conversions', compact('conversions', 'stats'));
    }

    /**
     * Show single conversion request
     */
    public function showConversion(ConversionRequest $conversion)
    {
        // Ensure user can only see their own conversions
        if ($conversion->user_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        return view('rewards.conversion-detail', compact('conversion'));
    }

    /**
     * Export user's transaction history
     */
    public function exportTransactions()
    {
        $user = Auth::user();
        $transactions = $user->piecesTransactions()->with('campaign')->get();

        $filename = 'mes_transactions_' . now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Date',
                'Type',
                'Montant',
                'Solde Avant',
                'Solde Après',
                'Campagne',
                'Description',
                'Référence',
            ]);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->type,
                    $transaction->amount,
                    $transaction->balance_before,
                    $transaction->balance_after,
                    $transaction->campaign->title ?? 'N/A',
                    $transaction->description ?? '',
                    $transaction->reference_id,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
