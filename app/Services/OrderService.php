<?php

namespace App\Services;

use App\Exceptions\EmptyCartException;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * カート内容から注文を確定するまでの一連の処理をまとめるサービス。
 *
 * 注文行(Order/OrderItem)の作成・在庫減算はDBトランザクションで一貫性を保ち、
 * 決済確定(PaymentService)とカートのクリアはトランザクション確定後に行う。
 */
class OrderService
{
    public function __construct(protected CartService $cart, protected PaymentService $payment)
    {
    }

    /**
     * カート内容から注文を作成し、決済確定処理を経て確定させる。
     *
     * @throws EmptyCartException カートが空の場合
     */
    public function createFromCart(User $user, array $shippingData): Order
    {
        $items = $this->cart->items();

        if ($items->isEmpty()) {
            throw new EmptyCartException();
        }

        $order = DB::transaction(function () use ($user, $shippingData, $items) {
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $items->sum('subtotal'),
                'status' => Order::STATUS_PENDING,
                'payment_method' => $shippingData['payment_method'],
                'shipping_name' => $shippingData['shipping_name'],
                'shipping_zip' => $shippingData['shipping_zip'] ?? null,
                'shipping_address' => $shippingData['shipping_address'],
                'shipping_tel' => $shippingData['shipping_tel'] ?? null,
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

                $item['product']->decrement('stock', $item['quantity']);
            }

            return $order;
        });

        // 決済確定処理(現状は即時承認のモック。実ゲートウェイ導入時はWebhook経由の非同期処理に置き換える)
        if ($this->payment->charge($order)) {
            $order->markAsPaid();
        }

        $this->cart->clear();

        return $order;
    }
}
