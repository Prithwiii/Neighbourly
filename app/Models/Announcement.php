<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    // Set the table name explicitly if it differs from the model name's plural form
    protected $table = 'announcements';

    // Only include actual user-submitted fields, NOT timestamps
    protected $fillable = [
        'headline',
        'content',
    ];

    // Timestamps are automatically managed by Laravel when timestamps() is enabled in migration
    // No need to list them in $fillable
}
