<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'invoice_number',
        'booking_id',
        'amount',
        'fine_amount',
        'total_amount',
        'due_date',
        'paid_date',
        'payment_method',
        'status',
        'midtrans_transaction_id',
        'midtrans_response',
        'payment_proof',
    ];

    protected $casts = [
        'due_date'          => 'date',
        'paid_date'         => 'date',
        'amount'            => 'decimal:2',
        'fine_amount'       => 'decimal:2',
        'total_amount'      => 'decimal:2',
        'midtrans_response' => 'array',
    ];

    /**
     * Generate invoice number otomatis
     * Format: INV-2024-001
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            $payment->invoice_number = self::generateInvoiceNumber();
            // Total = amount + denda
            $payment->total_amount = $payment->amount + $payment->fine_amount;
        });

        static::updating(function ($payment) {
            // Recalculate total saat update
            $payment->total_amount = $payment->amount + $payment->fine_amount;

            // Set paid_date otomatis saat status berubah jadi paid
            if ($payment->isDirty('status') && $payment->status === 'paid') {
                $payment->paid_date = now();
            }
        });
    }

    /**
     * Generate nomor invoice unik
     */
    public static function generateInvoiceNumber(): string
    {
        $year  = date('Y');
        $month = date('m');
        $count = self::whereYear('created_at', $year)
                     ->whereMonth('created_at', $month)
                     ->count() + 1;
        return 'INV-' . $year . $month . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Payment milik satu booking
     */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Cek apakah payment sudah overdue
     */
    public function isOverdue(): bool
    {
        return $this->status === 'unpaid' && $this->due_date->isPast();
    }

    /**
     * Hitung denda keterlambatan
     * Logika: 5000/hari setelah jatuh tempo
     */
    public function calculateFine(): float
    {
        if (!$this->isOverdue()) return 0;

        $daysLate  = now()->diffInDays($this->due_date);
        $finePerDay = 5000;

        return $daysLate * $finePerDay;
    }
}