<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EmergencyAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'alert_type',
        'title',
        'description',
        'media',
        'owner_phone',
        'location',
        'is_anonymous',
        'status',
        'admin_contacted_via',
        'admin_note',
        'reviewed_by',
        'reviewed_at',
        'admin_seen_at',
    ];

    protected function casts(): array
    {
        return [
            'media' => 'array',
            'is_anonymous' => 'boolean',
            'reviewed_at' => 'datetime',
            'admin_seen_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(EmergencyAlertComment::class);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
}
