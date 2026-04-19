<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobConfirmation extends Model
{
    protected $fillable = [
    'job_id',
    'enlisting_id',
    'employer_confirmed',
    'worker_confirmed'
];
}
