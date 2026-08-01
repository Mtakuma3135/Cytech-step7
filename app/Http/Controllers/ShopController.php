<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ShopController extends Controller
{
    /**
     * ショップ商品一覧画面（一般顧客向け）
     */
    public function index(Request $request): View
    {
        $query = Product::with('company');

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('product_name', 'like', "%{$keyword}%");
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $products = $query->orderBy('id', 'desc')->paginate(9)->withQueryString();
        $companies = Company::all();

        return view('shop.index', compact('products', 'companies'));
    }

    /**
     * ショップ商品詳細画面（一般顧客向け）
     */
    public function show(Product $product): View
    {
        $product->load('company');

        return view('shop.show', compact('product'));
    }
}
