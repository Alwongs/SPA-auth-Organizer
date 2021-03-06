<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;
        
    protected $fillable = ['title', 'description', 'result', 'date', 'company_id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
