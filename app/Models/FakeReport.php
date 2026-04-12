<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FakeReport extends Model
{
    protected $fillable = [
        'issue_id',
        'user_id',
    ];

    /**
     * Get the issue this report belongs to.
     */
    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }

    /**
     * Get the user who reported this.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
