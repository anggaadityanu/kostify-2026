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
        // BUAT SEMUA PERMISSION
        // ===========================
        $permissions = [
        // Properti
        'view_property', 'create_property',
        'edit_property', 'delete_property',

        // Kamar
        'view_room', 'create_room',
        'edit_room', 'delete_room',

        // Tenant
        'view_tenant', 'create_tenant',
        'edit_tenant', 'delete_tenant',

        // Booking
        'view_booking', 'create_booking',
        'edit_booking', 'delete_booking',
        'approve_booking',

        // Pembayaran
        'view_payment', 'create_payment',
        'edit_payment', 'delete_payment',
        'confirm_payment',

        // Laporan
        'view_report', 'export_report',

        // Komplain  ← ini yang kurang kemarin!
        'view_complaint', 'create_complaint',
        'reply_complaint', 'resolve_complaint',
        'delete_complaint',

        // Setting
        'view_setting', 'edit_setting',

        // User Management
        'view_user', 'create_user',
        'edit_user', 'delete_user',
    ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // ===========================
        // BUAT ROLE
        // ===========================

        // Super Admin → semua permission
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin']);
        $superAdmin->givePermissionTo(Permission::all());

        // Owner → permission terbatas (properti miliknya)
        $owner = Role::firstOrCreate(['name' => 'owner']);
        $owner->givePermissionTo([
            'view_property', 'edit_property',
            'view_room', 'edit_room',
            'view_tenant',
            'view_booking', 'approve_booking',
            'view_payment', 'confirm_payment',
            'view_report', 'export_report',
            'view_complaint', 'reply_complaint', 'resolve_complaint',
        ]);

        // Tenant → hanya portal tenant
        $tenant = Role::firstOrCreate(['name' => 'tenant']);
        $tenant->givePermissionTo([
            'view_booking', 'create_booking',
            'view_payment',
            'view_complaint', 'create_complaint',
        ]);

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

        $this->command->info('✅ Roles & Permissions berhasil dibuat!');
        $this->command->info('📧 Login: superadmin@kostify.com');
        $this->command->info('🔑 Password: password123');
    }
}