<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'full_name',
        'phone',
        'service_category',
        'location',
        'experience_years',
        'description',
        'availability_status',
        'availability_details',
        'document_path',
        'verification_status',
        'phone_otp',
        'phone_verified_at',
        'verified_at',
        'admin_seen_at',
        'admin_note',
        'fraud_note',
    ];

    protected function casts(): array
    {
        return [
            'phone_verified_at' => 'datetime',
            'verified_at' => 'datetime',
            'admin_seen_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ServiceProviderReview::class);
    }

    public function averageRating(): float
    {
        return round((float) $this->reviews()->avg('rating'), 1);
    }

    public function isVerified(): bool
    {
        return $this->verification_status === 'verified';
    }
}
