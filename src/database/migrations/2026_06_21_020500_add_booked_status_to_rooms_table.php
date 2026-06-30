<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tambah status 'booked' (sudah dibooking, menunggu approve/bayar)
     * di antara 'available' dan 'occupied'.
     *
     * Alur status kamar baru:
     * available -> booked (saat user booking) -> occupied (saat lunas & aktif)
     * booked -> available lagi (kalau booking ditolak/cancel/expired)
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE rooms MODIFY COLUMN status ENUM('available', 'booked', 'occupied', 'maintenance') NOT NULL DEFAULT 'available'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan dulu room yang 'booked' jadi 'available' supaya gak error pas turunin enum
        DB::table('rooms')->where('status', 'booked')->update(['status' => 'available']);

        DB::statement("ALTER TABLE rooms MODIFY COLUMN status ENUM('available', 'occupied', 'maintenance') NOT NULL DEFAULT 'available'");
    }
};