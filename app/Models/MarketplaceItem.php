<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketplaceItem extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'image',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * Get the user who created this listing.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}