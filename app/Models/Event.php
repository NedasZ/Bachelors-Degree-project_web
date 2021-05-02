<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'event_user', 'event_id', 'user_id')->withTimestamps()->withPivot('role','result');
    }

    public function map()
    {
        return $this->belongsTo(Map::class); // Ar klases modeli reikia sukurti?
    }

    public function gps_locations()
    {
        return $this->hasMany(Gps_location::class); 
    }
   
}
