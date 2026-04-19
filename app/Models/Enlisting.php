<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enlisting extends Model
{
    protected $fillable = [
        'name', 
        'contact', 
        'preferred_job', 
        'availability', 
        'user_id'
    ];
}
