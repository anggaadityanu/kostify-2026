<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplaintMessage extends Model
{
    protected $fillable = [
        'complaint_id',
        'user_id',
        'message',
    ];

    /**
     * Pesan ini milik satu komplain
     */
    public function complaint(): BelongsTo
    {
        return $this->belongsTo(Complaint::class);
    }

    /**
     * Pesan ini dikirim oleh satu user (bisa tenant, bisa admin/owner)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}