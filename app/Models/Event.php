<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'date', 'type', 'description', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
