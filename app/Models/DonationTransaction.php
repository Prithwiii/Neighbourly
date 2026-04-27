<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonationTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'donation_post_id',
        'user_id',
        'order_id',
        'transaction_id',
        'donor_name',
        'donor_email',
        'donor_phone',
        'amount',
        'currency',
        'payment_method',
        'status',
        'gateway_name',
        'gateway_url',
        'gateway_session_key',
        'gateway_validation_id',
        'gateway_payload',
        'gateway_response',
        'fail_reason',
        'paid_at',
        'canceled_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'gateway_payload' => 'array',
            'gateway_response' => 'array',
            'paid_at' => 'datetime',
            'canceled_at' => 'datetime',
        ];
    }

    public function donationPost(): BelongsTo
    {
        return $this->belongsTo(DonationPost::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
