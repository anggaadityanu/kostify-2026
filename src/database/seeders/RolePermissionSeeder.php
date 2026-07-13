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

        // Owner -> murni review & monitoring (view-only), nama HARUS sama
        // persis kayak yang dicek di app/Policies/*.php (format Filament
        // Shield: view_any_x, view_x). Sengaja TIDAK ada create/update/delete.
        // Pakai syncPermissions() (bukan givePermissionTo()) supaya kalau
        // sebelumnya role owner ini pernah dikasih permission lain (misal
        // create/update/delete), permission lama itu ikut ke-reset/hilang.
        $owner = Role::firstOrCreate(['name' => 'owner']);
        $owner->syncPermissions(array_filter([
            'view_any_property', 'view_property',
            'view_any_room', 'view_room',
            'view_any_tenant', 'view_tenant',
            'view_any_booking', 'view_booking',
            'view_any_payment', 'view_payment',
            'view_any_complaint', 'view_complaint',
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