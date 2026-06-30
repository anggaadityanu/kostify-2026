<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $owner = User::where('email', 'owner@kostify.com')->first();

        if (!$owner) {
            $this->command->warn('User owner@kostify.com tidak ditemukan, jalankan UserSeeder dulu.');
            return;
        }

        $properties = [
            [
                'name'        => 'Kos Putri Melati',
                'type'        => 'kos_putri',
                'description' => 'Kos putri nyaman dan aman, dekat kampus dan pusat perbelanjaan.',
                'address'     => 'Jl. Dipatiukur No. 45',
                'province'    => 'Jawa Barat',
                'city'        => 'Bandung',
                'district'    => 'Coblong',
                'latitude'    => -6.8915,
                'longitude'   => 107.6107,
                'facilities'  => ['wifi', 'ac', 'parking', 'security', 'laundry'],
                'status'      => 'active',
            ],
            [
                'name'        => 'Kos Putra Mawar',
                'type'        => 'kos_putra',
                'description' => 'Kos putra strategis dengan akses mudah ke berbagai fasilitas umum.',
                'address'     => 'Jl. Sukajadi No. 12',
                'province'    => 'Jawa Barat',
                'city'        => 'Bandung',
                'district'    => 'Sukajadi',
                'latitude'    => -6.8975,
                'longitude'   => 107.5965,
                'facilities'  => ['wifi', 'parking', 'security'],
                'status'      => 'active',
            ],
            [
                'name'        => 'Kontrakan Anggrek',
                'type'        => 'kontrakan',
                'description' => 'Kontrakan keluarga dengan halaman luas dan lingkungan tenang.',
                'address'     => 'Jl. Kaliurang KM 5',
                'province'    => 'D.I. Yogyakarta',
                'city'        => 'Yogyakarta',
                'district'    => 'Sleman',
                'latitude'    => -7.7956,
                'longitude'   => 110.3695,
                'facilities'  => ['wifi', 'parking', 'kitchen'],
                'status'      => 'active',
            ],
        ];

        foreach ($properties as $data) {
            Property::firstOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['user_id' => $owner->id])
            );
        }

        $this->command->info('Properties berhasil dibuat!');
    }
}