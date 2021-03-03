<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;


    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user', 'event_id', 'user_id');
    }

    public function map()
    {
        return $this->belongsTo(Map::class); // Ar klases modeli reikia sukurti?
    }

    public function gps_locations()
    {
        return $this->hasMany(gps_locations::class); // Ar klases modeli reikia sukurti?
    }
}
