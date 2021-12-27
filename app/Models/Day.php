<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Day extends Model
{
    use HasFactory;

    protected $fillable = ['date', 'remains_pre', 'odo_pre', 'fuel', 'odo_post', 'remains_post', 'is_day_off', 'is_trip', 'comment', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
