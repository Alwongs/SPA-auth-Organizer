<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    
    protected $fillable = ['model', 'fuel_tank', 'car_number', 'region', 'summer_norm', 'winter_norm', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function days()
    {
        return $this->hasMany(Day::class)->orderBy('date', 'asc');
    }
}
