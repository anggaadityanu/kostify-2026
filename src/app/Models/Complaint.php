<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Complaint extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'ticket_number',
        'tenant_id',
        'room_id',
        'title',
        'description',
        'category',
        'priority',
        'status',
    ];

    /**
     * Generate ticket number otomatis
     * Format: TKT-2024-001
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($complaint) {
            $complaint->ticket_number = self::generateTicketNumber();
        });
    }

    public static function generateTicketNumber(): string
    {
        $year  = date('Y');
        $count = self::whereYear('created_at', $year)->count() + 1;
        return 'TKT-' . $year . '-' . str_pad($count, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Complaint milik satu tenant
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Complaint terkait satu kamar
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}