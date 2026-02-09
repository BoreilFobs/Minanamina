<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SyncUserStats extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'users:sync-stats {--user= : Specific user ID to sync} {--clean-orphans : Remove orphan referral transactions}';

    /**
     * The console command description.
     */
    protected $description = 'Sync user statistics with actual database records';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user');
        $cleanOrphans = $this->option('clean-orphans');

        if ($userId) {
            $users = User::where('id', $userId)->get();
        } else {
            $users = User::all();
        }

        $this->info("Syncing stats for {$users->count()} user(s)...");

        $bar = $this->output->createProgressBar($users->count());

        foreach ($users as $user) {
            // Calculate actual referral stats
            $actualReferredCount = $user->referredUsers()->count();
            $actualReferralEarnings = $user->piecesTransactions()
                ->where('type', 'referral_bonus')
                ->where('amount', '>', 0)
                ->sum('amount');
            
            // Calculate actual lifetime earnings
            $actualLifetimeEarnings = $user->piecesTransactions()
                ->where('amount', '>', 0)
                ->sum('amount');
            
            // Calculate actual campaigns completed
            $actualCampaignsCompleted = $user->participations()
                ->where('status', 'completed')
                ->count();

            // Clean orphan referral transactions if requested
            if ($cleanOrphans && $actualReferredCount == 0 && $actualReferralEarnings > 0) {
                $orphanTransactions = $user->piecesTransactions()
                    ->where('type', 'referral_bonus')
                    ->get();
                
                $this->newLine();
                $this->warn("User {$user->name} (ID: {$user->id}) has {$orphanTransactions->count()} orphan referral transaction(s)");
                
                if ($this->confirm("Delete these orphan transactions?", true)) {
                    foreach ($orphanTransactions as $tx) {
                        // Reverse the balance if needed
                        $user->decrement('pieces_balance', $tx->amount);
                        $tx->delete();
                    }
                    $actualReferralEarnings = 0;
                    $this->info("Deleted orphan transactions for user {$user->name}");
                }
            }

            // Update cached values
            $user->update([
                'total_referrals' => $actualReferredCount,
                'referral_earnings' => $actualReferralEarnings,
                'lifetime_earnings' => $actualLifetimeEarnings,
                'total_campaigns_completed' => $actualCampaignsCompleted,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info('User stats synced successfully!');

        return Command::SUCCESS;
    }
}
