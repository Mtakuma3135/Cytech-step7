<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Collection;

/**
 * セッションを利用した簡易カート管理サービス
 */
class CartService
{
    protected const SESSION_KEY = 'cart';

    /**
     * カート内の生データ（product_id => quantity）を取得
     */
    protected function raw(): array
    {
        return session(self::SESSION_KEY, []);
    }

    protected function save(array $cart): void
    {
        session([self::SESSION_KEY => $cart]);
    }

    /**
     * カートに商品を追加（既にあれば数量を加算）
     */
    public function add(int $productId, int $quantity): void
    {
        $cart = $this->raw();
        $cart[$productId] = ($cart[$productId] ?? 0) + $quantity;
        $this->save($cart);
    }

    /**
     * カート内の商品数量を更新
     */
    public function update(int $productId, int $quantity): void
    {
        $cart = $this->raw();

        if ($quantity <= 0) {
            unset($cart[$productId]);
        } else {
            $cart[$productId] = $quantity;
        }

        $this->save($cart);
    }

    /**
     * カートから商品を削除
     */
    public function remove(int $productId): void
    {
        $cart = $this->raw();
        unset($cart[$productId]);
        $this->save($cart);
    }

    /**
     * カートを空にする
     */
    public function clear(): void
    {
        $this->save([]);
    }

    /**
     * 商品情報を紐付けたカート明細一覧を取得
     */
    public function items(): Collection
    {
        $cart = $this->raw();

        if (empty($cart)) {
            return collect();
        }

        $products = Product::with('company')->whereIn('id', array_keys($cart))->get()->keyBy('id');

        return collect($cart)
            ->map(function ($quantity, $productId) use ($products) {
                $product = $products->get($productId);

                if (! $product) {
                    return null;
                }

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'subtotal' => $product->price * $quantity,
                ];
            })
            ->filter()
            ->values();
    }

    /**
     * カート内の合計金額
     */
    public function total(): int
    {
        return $this->items()->sum('subtotal');
    }

    /**
     * カート内の商品点数（種類ではなく数量の合計）
     */
    public function count(): int
    {
        return array_sum($this->raw());
    }
}
