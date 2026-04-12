<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $fillable = [
        'referrer_user_id',
        'referred_user_id',
        'order_id',
        'commission_rate',
        'commission_amount',
        'status',
    ];
}
