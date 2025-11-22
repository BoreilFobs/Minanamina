<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['phone' => '+23700000000'],
            [
                'name' => 'Administrateur',
                'phone_verified_at' => now(),
                'password' => Hash::make('password'),
                'is_admin' => true,
                'role' => 'superadmin',
                'pieces_balance' => 0,
                'referral_code' => Str::upper(Str::random(8)),
                'status' => 'active',
            ]
        );

        $this->command->info('SuperAdmin user created/updated successfully!');
        $this->command->info('Phone: +23700000000');
        $this->command->info('Password: password');
        $this->command->info('Role: superadmin');
    }
}
