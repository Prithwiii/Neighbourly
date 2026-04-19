<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'name', 
        'contact', 
        'job_datetime', 
        'location', 
        'description', 
        'user_id', 
        'status'
    ];

    public function confirmations()
    {
        return $this->hasMany(\App\Models\JobConfirmation::class);
    }
    public function selectedEnlisting()
    {
        return $this->belongsTo(\App\Models\Enlisting::class, 'selected_enlisting_id');
    }
}
