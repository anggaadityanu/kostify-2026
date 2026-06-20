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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();      // nomor invoice
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);                // jumlah tagihan
            $table->decimal('fine_amount', 12, 2)->default(0); // denda
            $table->decimal('total_amount', 12, 2);          // total bayar
            $table->date('due_date');                        // jatuh tempo
            $table->date('paid_date')->nullable();           // tanggal bayar
            $table->enum('payment_method', [
                'transfer',
                'qris',
                'cash',
                'midtrans'
            ])->nullable();
            $table->enum('status', [
                'unpaid',        // belum bayar
                'pending',       // menunggu konfirmasi
                'paid',          // lunas
                'overdue',       // menunggak
                'cancelled'
            ])->default('unpaid');
            $table->string('midtrans_transaction_id')->nullable();
            $table->json('midtrans_response')->nullable();   // response dari midtrans
            $table->string('payment_proof')->nullable();     // bukti transfer
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
