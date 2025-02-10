<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Generation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'style',
        'duration',
        'happiness_level',
        'energy_level',
        'status',
        'file_path'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
