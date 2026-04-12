<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Download extends Model
{
    protected $fillable = [
        'user_id',
        'order_item_id',
        'token_hash',
        'expires_at',
        'downloaded_at',
        'download_count',
        'max_downloads',
    ];
}
