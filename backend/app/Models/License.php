<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $fillable = ['user_id', 'order_item_id', 'product_id', 'license_key', 'status'];
}
