<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckInRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'check_in_assignment_id',
        'reviewer_id',
        'rating',
        'review_text',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(CheckInAssignment::class, 'check_in_assignment_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
