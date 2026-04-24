<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VolunteerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_verified',
        'verified_id_path',
        'bio',
        'address',
        'completed_check_ins',
        'average_rating',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'average_rating' => 'float',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function updateStats(): void
    {
        $completed = CheckInAssignment::where('volunteer_id', $this->user_id)
            ->where('status', 'completed')
            ->count();

        $avgRating = CheckInRating::whereHas('assignment', function ($q) {
            $q->where('volunteer_id', $this->user_id);
        })->avg('rating');

        $this->update([
            'completed_check_ins' => $completed,
            'average_rating' => $avgRating,
        ]);
    }
}
