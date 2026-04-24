<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CheckInRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'work_type',
        'urgency_level',
        'latitude',
        'longitude',
        'location_name',
        'phone_number',
        'status',
        'preferred_date',
    ];

    protected $casts = [
        'preferred_date' => 'datetime',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(CheckInAssignment::class);
    }

    public function activeAssignment(): HasOne
    {
        return $this->hasOne(CheckInAssignment::class)
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->latestOfMany('assigned_at');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(CheckInMessage::class);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    public function scopeOrderByUrgency($query)
    {
        return $query->orderByRaw("FIELD(urgency_level, 'high', 'medium', 'low')");
    }

    public function scopeOrderByNewest($query)
    {
        return $query->latest('created_at');
    }

    public function distanceFrom(float $lat, float $lng): float
    {
        if (!$this->latitude || !$this->longitude) {
            return PHP_FLOAT_MAX;
        }

        $earthRadius = 6371;
        $latFrom = deg2rad($lat);
        $latTo = deg2rad($this->latitude);
        $dLat = deg2rad($this->latitude - $lat);
        $dLng = deg2rad($this->longitude - $lng);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos($latFrom) * cos($latTo) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * asin(sqrt($a));

        return $earthRadius * $c;
    }
}
