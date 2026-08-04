<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * 注文履歴一覧（自分の注文のみ）
     */
    public function index(Request $request): View
    {
        $orders = $request->user()->orders()->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    /**
     * 注文詳細（自分の注文のみ）
     */
    public function show(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->load('items');

        return view('orders.show', compact('order'));
    }
}
