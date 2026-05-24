<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Wallet;
use App\Enums\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Admin User
        $admin = User::updateOrCreate(
            ['email' => 'admin.aurumgold@yopmail.com'],
            [
                'name' => 'Aurum Admin',
                'password' => Hash::make('Nova7401'),
                'role' => Role::ADMIN->value,
                'is_active' => true,
            ]
        );

        // 2. Assign Spatie Admin Role
        if (!$admin->hasRole('admin')) {
            $admin->assignRole('admin');
        }

        // 3. Create/Update Wallet with test assets
//        Wallet::updateOrCreate(
//            ['user_id' => $admin->id],
//            [
//                'balance' => 50000.00,
//                'gold_balance' => 12.5000,
//                'silver_balance' => 250.0000,
//                'platinum_balance' => 8.2500,
//                'palladium_balance' => 3.1000,
//            ]
//        );
    }
}
