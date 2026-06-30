<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]
            ->forgetCachedPermissions();

        // ===========================
        // BUAT ROLE
        // ===========================

        // Super Admin -> semua permission yang ke-generate Filament Shield
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Owner -> permission terbatas, nama HARUS sama persis kayak yang
        // dicek di app/Policies/*.php (format Filament Shield: view_any_x,
        // view_x, create_x, update_x, delete_x, delete_any_x)
        $owner = Role::firstOrCreate(['name' => 'owner']);
        $owner->givePermissionTo(array_filter([
            'view_any_property', 'view_property', 'update_property',
            'view_any_room', 'view_room', 'update_room',
            'view_any_tenant', 'view_tenant',
            'view_any_booking', 'view_booking', 'update_booking',
            'view_any_payment', 'view_payment', 'update_payment',
            'view_any_complaint', 'view_complaint', 'update_complaint',
        ], fn ($name) => Permission::where('name', $name)->exists()));

        // Tenant -> gak akses admin panel sama sekali, tapi tetep
        // disiapkan permission dasarnya kalau suatu saat dibutuhkan
        $tenant = Role::firstOrCreate(['name' => 'tenant']);
        $tenant->givePermissionTo(array_filter([
            'view_any_booking', 'view_booking', 'create_booking',
            'view_any_payment', 'view_payment',
            'view_any_complaint', 'view_complaint', 'create_complaint',
        ], fn ($name) => Permission::where('name', $name)->exists()));

        // ===========================
        // BUAT USER SUPER ADMIN
        // ===========================
        $adminUser = User::firstOrCreate(
            ['email' => 'superadmin@kostify.com'],
            [
                'name'      => 'Super Admin',
                'password'  => bcrypt('password123'),
                'is_active' => true,
            ]
        );
        $adminUser->assignRole('super_admin');

        $this->command->info('Roles & Permissions berhasil dibuat!');
        $this->command->info('Login: superadmin@kostify.com');
        $this->command->info('Password: password123');
    }
}