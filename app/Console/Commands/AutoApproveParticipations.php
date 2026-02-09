<?php

namespace App\Console\Commands;

use App\Models\CampaignParticipation;
use App\Models\UserPiecesTransaction;
use App\Services\BadgeService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AutoApproveParticipations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'participations:auto-approve';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically approve pending participations after 15-20 minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get pending participations that started between 15-20 minutes ago
        // We use a random delay between 15-20 minutes for each participation
        $pendingParticipations = CampaignParticipation::where('status', 'pending')
            ->where('started_at', '<=', now()->subMinutes(15))
            ->with(['campaign', 'user'])
            ->get();

        $approvedCount = 0;
        $errorCount = 0;

        foreach ($pendingParticipations as $participation) {
            // Calculate the random approval time (between 15-20 minutes after start)
            $minutesSinceStart = now()->diffInMinutes($participation->started_at);
            
            // Generate a consistent random delay based on participation ID
            // This ensures the same participation always has the same delay
            $randomDelay = 15 + ($participation->id % 6); // 15-20 minutes
            
            // Only approve if enough time has passed
            if ($minutesSinceStart < $randomDelay) {
                continue;
            }

            DB::beginTransaction();
            try {
                $campaign = $participation->campaign;
                $user = $participation->user;

                if (!$campaign || !$user) {
                    Log::warning("Auto-approve skipped: Campaign or User not found for participation {$participation->id}");
                    continue;
                }

                $piecesToAward = $campaign->pieces_reward;

                // Update participation status
                $participation->update([
                    'status' => 'completed',
                    'completed_at' => now(),
                    'pieces_earned' => $piecesToAward,
                    'time_spent_minutes' => $minutesSinceStart,
                ]);

                // Award pieces to user
                $user->increment('pieces_balance', $piecesToAward);

                // Create transaction record
                UserPiecesTransaction::create([
                    'user_id' => $user->id,
                    'amount' => $piecesToAward,
                    'type' => 'earned',
                    'description' => "Campagne complétée: {$campaign->title}",
                    'reference_type' => 'campaign_participation',
                    'reference_id' => $participation->id,
                ]);

                // Check and award badges
                try {
                    $badgeService = app(BadgeService::class);
                    $badgeService->checkAndAwardBadges($user);
                } catch (\Exception $e) {
                    Log::warning("Badge service error for user {$user->id}: " . $e->getMessage());
                }

                DB::commit();
                $approvedCount++;

                Log::info("Auto-approved participation {$participation->id} for user {$user->id}, awarded {$piecesToAward} pieces");

            } catch (\Exception $e) {
                DB::rollBack();
                $errorCount++;
                Log::error("Auto-approve error for participation {$participation->id}: " . $e->getMessage());
            }
        }

        $this->info("Auto-approval complete: {$approvedCount} approved, {$errorCount} errors");
        
        return Command::SUCCESS;
    }
}
