<?php

namespace App\Services;

use App\Models\License;
use App\Models\OrderItem;
use Illuminate\Support\Str;

class LicenseService
{
    public function generateForOrderItem(OrderItem $item, int $userId): License
    {
        return License::firstOrCreate(
            ['order_item_id' => $item->id],
            [
                'user_id' => $userId,
                'product_id' => $item->product_id,
                'license_key' => strtoupper(Str::random(16) . '-' . Str::random(16)),
                'status' => 'active',
            ]
        );
    }
}
