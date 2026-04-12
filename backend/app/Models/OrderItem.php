<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'product_id', 'unit_price', 'quantity'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function license()
    {
        return $this->hasOne(License::class);
    }
}
