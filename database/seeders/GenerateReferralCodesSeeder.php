<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class GenerateReferralCodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::whereNull('referral_code')->get();

        foreach ($users as $user) {
            $user->generateReferralCode();
            $this->command->info("Generated referral code {$user->referral_code} for {$user->name}");
        }

        $this->command->info("Generated referral codes for {$users->count()} users");
    }
}
