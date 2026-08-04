<?php

namespace App\Http\Controllers;

use App\Exceptions\EmptyCartException;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(protected CartService $cart, protected OrderService $orders)
    {
    }

    /**
     * 購入手続き（配送先・支払い方法入力）画面
     */
    public function index(): View|RedirectResponse
    {
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'カートが空です');
        }

        $total = $this->cart->total();

        return view('checkout.index', compact('items', 'total'));
    }

    /**
     * 注文を確定し、注文完了画面へ
     */
    public function store(CheckoutRequest $request): RedirectResponse
    {
        try {
            $order = $this->orders->createFromCart($request->user(), $request->validated());
        } catch (EmptyCartException $e) {
            return redirect()->route('cart.index')->with('error', 'カートが空です');
        } catch (\Throwable $e) {
            Log::error($e->getMessage());

            return redirect()->route('checkout.index')->with('error', '注文処理に失敗しました。');
        }

        return redirect()->route('checkout.complete', $order);
    }

    /**
     * 注文完了画面
     */
    public function complete(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        $order->load('items');

        return view('checkout.complete', compact('order'));
    }
}
