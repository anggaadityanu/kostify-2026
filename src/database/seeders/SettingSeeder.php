<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::query()->firstOrCreate([], [
            'address'           => 'Indonesia',
            'phone'             => '+62 821-1498-1216',
            'whatsapp'          => '+62 821-1498-1216',
            'email'             => 'info@kostify.com',
            'about_title'       => 'Tentang Kostify',
            'about_description' => 'Kostify adalah platform digital terpercaya untuk mencari, booking, dan mengelola kos & kontrakan di Indonesia. Kami hadir untuk memudahkan proses sewa-menyewa properti secara transparan dan efisien.',
            'about_features'    => [
                'Booking kamar mudah & cepat',
                'Pembayaran online aman via Midtrans',
                'Laporan & notifikasi otomatis',
                'Support 24/7 untuk tenant',
            ],
            
            'home_hero_title'       => 'Temukan Kos & Kontrakan Impian Anda',
            'home_hero_subtitle'    => 'Platform terpercaya untuk mencari, booking, dan mengelola kos & kontrakan dengan mudah, transparan, dan terjangkau.',
            'home_cta_title'        => 'Siap Temukan Kos Impian?',
            'home_cta_description'  => 'Daftar sekarang dan mulai cari kamar yang sesuai kebutuhan Anda dengan mudah.',
        ]);
    }
}