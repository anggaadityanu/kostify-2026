<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'email'    => 'tenant@kostify.com', // sudah dibuat di UserSeeder
                'name'     => 'Tenant Test',
                'nik'      => '3204010101990001',
                'phone'    => '081234567890',
                'gender'   => 'male',
                'occupation' => 'Mahasiswa',
                'emergency_contact_name'     => 'Bapak Tenant',
                'emergency_contact_phone'    => '081234567891',
                'emergency_contact_relation' => 'Ayah',
                'address_origin' => 'Jl. Merdeka No. 1, Jakarta',
            ],
            [
                'email'    => 'budi.santoso@example.com',
                'name'     => 'Budi Santoso',
                'nik'      => '3204010101990002',
                'phone'    => '081234567892',
                'gender'   => 'male',
                'occupation' => 'Mahasiswa',
                'emergency_contact_name'     => 'Ibu Santoso',
                'emergency_contact_phone'    => '081234567893',
                'emergency_contact_relation' => 'Ibu',
                'address_origin' => 'Jl. Sudirman No. 5, Surabaya',
            ],
            [
                'email'    => 'sari.wulandari@example.com',
                'name'     => 'Sari Wulandari',
                'nik'      => '3204010101990003',
                'phone'    => '081234567894',
                'gender'   => 'female',
                'occupation' => 'Karyawan Swasta',
                'emergency_contact_name'     => 'Ani Wulandari',
                'emergency_contact_phone'    => '081234567895',
                'emergency_contact_relation' => 'Kakak',
                'address_origin' => 'Jl. Pahlawan No. 8, Semarang',
            ],
        ];

        foreach ($data as $item) {
            $user = User::firstOrCreate(
                ['email' => $item['email']],
                [
                    'name'      => $item['name'],
                    'password'  => bcrypt('password123'),
                    'is_active' => true,
                ]
            );

            if (!$user->hasRole('tenant')) {
                $user->assignRole('tenant');
            }

            Tenant::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nik'         => $item['nik'],
                    'phone'       => $item['phone'],
                    'gender'      => $item['gender'],
                    'occupation'  => $item['occupation'],
                    'emergency_contact_name'     => $item['emergency_contact_name'],
                    'emergency_contact_phone'    => $item['emergency_contact_phone'],
                    'emergency_contact_relation' => $item['emergency_contact_relation'],
                    'address_origin' => $item['address_origin'],
                ]
            );
        }

        $this->command->info('Tenants berhasil dibuat!');
    }
}