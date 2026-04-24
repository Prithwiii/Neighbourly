<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CheckInAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'check_in_request_id',
        'volunteer_id',
        'status',
        'assigned_at',
        'confirmed_at',
        'completed_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'confirmed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function checkInRequest(): BelongsTo
    {
        return $this->belongsTo(CheckInRequest::class);
    }

    public function volunteer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'volunteer_id');
    }

    public function rating(): HasOne
    {
        return $this->hasOne(CheckInRating::class);
    }

    public function confirm(): void
    {
        $this->update([
            'status' => 'confirmed',
            'confirmed_at' => now(),
        ]);

        $this->checkInRequest->update(['status' => 'in_progress']);
    }

    public function complete(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        $this->checkInRequest->update(['status' => 'completed']);
    }
}
