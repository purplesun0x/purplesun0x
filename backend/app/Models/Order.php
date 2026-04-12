<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'subtotal',
        'total',
        'status',
        'payment_provider',
        'provider_reference',
        'paid_at',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
