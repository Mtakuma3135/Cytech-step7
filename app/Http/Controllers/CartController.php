<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(protected CartService $cart)
    {
    }

    /**
     * カート画面を表示
     */
    public function index(): View
    {
        $items = $this->cart->items();
        $total = $this->cart->total();

        return view('cart.index', compact('items', 'total'));
    }

    /**
     * カートに商品を追加
     */
    public function add(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'quantity' => 'nullable|integer|min:1',
        ]);

        $quantity = (int) ($request->input('quantity', 1));
        $quantity = max(1, min($quantity, $product->stock ?: 1));

        $this->cart->add($product->id, $quantity);

        return redirect()->route('cart.index')->with('success', 'カートに追加しました');
    }

    /**
     * カート内商品の数量を更新
     */
    public function update(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $this->cart->update($product->id, (int) $request->input('quantity'));

        return redirect()->route('cart.index')->with('success', 'カートを更新しました');
    }

    /**
     * カートから商品を削除
     */
    public function remove(Product $product): RedirectResponse
    {
        $this->cart->remove($product->id);

        return redirect()->route('cart.index')->with('success', '商品をカートから削除しました');
    }
}
