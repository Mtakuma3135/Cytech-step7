<?php

namespace App\Services;

use App\Models\CartItem;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * カート管理サービス（DB永続化）。
 *
 * ログインユーザーは user_id、未ログイン（ゲスト）は session_id でカートを識別する。
 * ゲストがカートに入れた後ログインした場合は mergeGuestCartIntoUser() でユーザーのカートへ統合する。
 */
class CartService
{
    /**
     * 現在のオーナー（ログインユーザー or ゲストセッション）のカート行に絞り込むクエリ
     */
    protected function ownerQuery(): Builder
    {
        if (Auth::check()) {
            return CartItem::query()->where('user_id', Auth::id());
        }

        return CartItem::query()->whereNull('user_id')->where('session_id', session()->getId());
    }

    /**
     * カートに商品を追加（既にあれば数量を加算）
     */
    public function add(int $productId, int $quantity): void
    {
        $item = $this->ownerQuery()->where('product_id', $productId)->first();

        if ($item) {
            $item->increment('quantity', $quantity);
            return;
        }

        CartItem::create([
            'user_id' => Auth::id(),
            'session_id' => Auth::check() ? null : session()->getId(),
            'product_id' => $productId,
            'quantity' => $quantity,
        ]);
    }

    /**
     * カート内の商品数量を更新
     */
    public function update(int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->remove($productId);
            return;
        }

        $this->ownerQuery()->where('product_id', $productId)->update(['quantity' => $quantity]);
    }

    /**
     * カートから商品を削除
     */
    public function remove(int $productId): void
    {
        $this->ownerQuery()->where('product_id', $productId)->delete();
    }

    /**
     * カートを空にする
     */
    public function clear(): void
    {
        $this->ownerQuery()->delete();
    }

    /**
     * 商品情報を紐付けたカート明細一覧を取得
     */
    public function items(): Collection
    {
        return $this->ownerQuery()
            ->with('product.company')
            ->get()
            ->filter(fn (CartItem $item) => $item->product !== null)
            ->map(fn (CartItem $item) => [
                'product' => $item->product,
                'quantity' => $item->quantity,
                'subtotal' => $item->product->price * $item->quantity,
            ])
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
        return (int) $this->ownerQuery()->sum('quantity');
    }

    /**
     * ログイン時に、直前まで使っていたゲストセッションのカートをユーザーのカートへ統合する。
     *
     * 呼び出し側は Auth::attempt() 等でログインする「前」の session()->getId() を
     * $guestSessionId として渡すこと。SessionGuard::login() はセッションIDを
     * 再生成してから Login イベントを発火するため、イベントリスナー内で
     * session()->getId() を読んでも既に新しいIDになっており、ゲストカートの行に
     * マッチしない。
     */
    public function mergeGuestCartIntoUser(int $userId, string $guestSessionId): void
    {
        $guestItems = CartItem::whereNull('user_id')
            ->where('session_id', $guestSessionId)
            ->get();

        foreach ($guestItems as $guestItem) {
            $userItem = CartItem::where('user_id', $userId)
                ->where('product_id', $guestItem->product_id)
                ->first();

            if ($userItem) {
                $userItem->increment('quantity', $guestItem->quantity);
                $guestItem->delete();
            } else {
                $guestItem->update(['user_id' => $userId, 'session_id' => null]);
            }
        }
    }
}
