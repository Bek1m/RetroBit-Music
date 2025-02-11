<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Generation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'style',
        'happiness_level',
        'energy_level',
        'velocity_min', 
        'velocity_max', 
        'tempo', 
        'generation_length',
        'output_name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}