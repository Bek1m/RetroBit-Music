<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'credit_package_id',
        'transaction_type',
        'amount',
        'status',
        'payment_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creditPackage()
    {
        return $this->belongsTo(CreditPackage::class);
    }
}
