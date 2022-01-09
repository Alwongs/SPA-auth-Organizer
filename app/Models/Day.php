<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'remains_pre', 'odo_pre', 'fuel', 'odo_post', 'remains_post', 'work_in_red', 'is_trip', 'red_day', 'comment', 'car_id'];

    public function car()
    {
        return $this->belongsTo(Car::class);
    }
}
