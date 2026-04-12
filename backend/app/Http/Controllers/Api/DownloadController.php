<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Download;
use App\Models\OrderItem;
use App\Services\DownloadTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function index(Request $request)
    {
        $items = OrderItem::whereHas('order', fn ($q) => $q->where('user_id', $request->user()->id)->where('status', 'paid'))
            ->with(['product:id,name', 'license'])
            ->get();

        return response()->json($items);
    }

    public function generateToken(Request $request, OrderItem $orderItem, DownloadTokenService $tokenService)
    {
        abort_if($orderItem->order->user_id !== $request->user()->id, 403, 'Unauthorized');
        abort_if($orderItem->order->status !== 'paid', 422, 'Order not paid');

        $result = $tokenService->create($orderItem->load('product'), $request->user()->id);

        return response()->json([
            'download_url' => url('/api/downloads/file/' . $result['token']),
            'expires_at' => $result['download']->expires_at,
        ]);
    }

    public function download(string $token, DownloadTokenService $tokenService)
    {
        $download = $tokenService->consume($token);
        $orderItem = OrderItem::with('product')->findOrFail($download->order_item_id);

        return Storage::disk('private')->download($orderItem->product->script_file_path);
    }
}
