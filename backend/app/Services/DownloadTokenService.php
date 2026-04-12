<?php

namespace App\Services;

use App\Models\Download;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DownloadTokenService
{
    public function create(OrderItem $item, int $userId): array
    {
        $plain = Str::random(64);
        $download = Download::create([
            'user_id' => $userId,
            'order_item_id' => $item->id,
            'token_hash' => hash('sha256', $plain),
            'expires_at' => Carbon::now()->addHours(24),
            'max_downloads' => $item->product->download_limit,
        ]);

        return ['token' => $plain, 'download' => $download];
    }

    public function consume(string $plainToken): Download
    {
        $download = Download::where('token_hash', hash('sha256', $plainToken))->firstOrFail();

        abort_if(now()->greaterThan($download->expires_at), 410, 'Link expired.');
        abort_if($download->download_count >= $download->max_downloads, 429, 'Download limit reached.');

        $download->increment('download_count');
        $download->update(['downloaded_at' => now()]);

        return $download;
    }
}
