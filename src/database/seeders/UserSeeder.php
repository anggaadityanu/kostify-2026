<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@kostify.com'],
            [
                'name'      => 'Super Admin',
                'password'  => bcrypt('password123'),
                'is_active' => true,
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Owner
        $owner = User::firstOrCreate(
            ['email' => 'owner@kostify.com'],
            [
                'name'      => 'Owner Kostify',
                'password'  => bcrypt('password123'),
                'is_active' => true,
            ]
        );
        $owner->assignRole('owner');

        // Tenant
        $tenant = User::firstOrCreate(
            ['email' => 'tenant@kostify.com'],
            [
                'name'      => 'Tenant Test',
                'password'  => bcrypt('password123'),
                'is_active' => true,
            ]
        );
        $tenant->assignRole('tenant');

        $this->command->info('✅ Users berhasil dibuat!');
    }
}