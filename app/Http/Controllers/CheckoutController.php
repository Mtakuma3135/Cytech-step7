<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(protected CartService $cart, protected PaymentService $payment)
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
    public function store(Request $request): RedirectResponse
    {
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'カートが空です');
        }

        $validated = $request->validate([
            'shipping_name' => 'required|string|max:255',
            'shipping_zip' => 'nullable|string|max:20',
            'shipping_address' => 'required|string|max:255',
            'shipping_tel' => 'nullable|string|max:20',
            'payment_method' => 'required|in:cod,credit_card',
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_price' => $this->cart->total(),
                'status' => Order::STATUS_PENDING,
                'payment_method' => $validated['payment_method'],
                'shipping_name' => $validated['shipping_name'],
                'shipping_zip' => $validated['shipping_zip'] ?? null,
                'shipping_address' => $validated['shipping_address'],
                'shipping_tel' => $validated['shipping_tel'] ?? null,
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'product_name' => $item['product']->product_name,
                    'price' => $item['product']->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ]);

                // 在庫減算
                $item['product']->decrement('stock', $item['quantity']);
            }

            DB::commit();

            // 決済確定処理（現状は即時承認のモック。実ゲートウェイ導入時はWebhook経由の非同期処理に置き換える）
            if ($this->payment->charge($order)) {
                $order->markAsPaid();
            }

            $this->cart->clear();

            return redirect()->route('checkout.complete', $order);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error($e->getMessage());

            return redirect()->route('checkout.index')->with('error', '注文処理に失敗しました。');
        }
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
