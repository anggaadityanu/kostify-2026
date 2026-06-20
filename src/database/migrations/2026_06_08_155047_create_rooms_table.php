<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('room_number');                   // nomor kamar
            $table->enum('type', ['standard', 'deluxe', 'vip']);
            $table->text('description')->nullable();
            $table->decimal('price_monthly', 12, 2);         // harga per bulan
            $table->decimal('price_yearly', 12, 2)->nullable();
            $table->integer('capacity')->default(1);         // kapasitas orang
            $table->decimal('size', 8, 2)->nullable();       // ukuran m2
            $table->json('facilities')->nullable();          // fasilitas khusus kamar
            $table->enum('status', [
                'available',     // tersedia
                'occupied',      // terisi
                'maintenance'    // perbaikan
            ])->default('available');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
