<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referrer_id',
        'referred_user_id',
        'commission',
        'status',
        'order_id',
    ];

    protected $casts = [
        'commission' => 'decimal:2',
    ];
}
