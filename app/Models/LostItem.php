<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LostItem extends Model
{
    protected $fillable = [
     'username',  
     'phone',
     'description',
     'location',
     'date_lost',
     'image'
    ];
}
