<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
        
    protected $fillable = ['season', 'default_car_id', 'percent', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
