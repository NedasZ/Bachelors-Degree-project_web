<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gps_location extends Model
{
    use HasFactory;

    //hello
    
    protected $fillable = [
        'user_id',
        'event_id',
        'locations',
    ];

}
