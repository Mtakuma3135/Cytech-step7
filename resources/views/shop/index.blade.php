<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- ヒーロー -->
            <div class="bg-gradient-to-r from-cyan-500 to-blue-600 rounded-2xl px-8 py-10 mb-10 text-white shadow">
                <h1 class="text-3xl font-bold mb-2">ドリンクストア</h1>
                <p class="text-cyan-50">全国のメーカーのお気に入りドリンクをお届けします。</p>
            </div>

            <!-- 検索・絞り込み -->
            <form method="GET" action="{{ route('shop.index') }}" class="mb-8 flex flex-wrap gap-3 bg-white p-4 rounded-xl shadow-sm">
                <input
                    type="text"
                    name="keyword"
                    value="{{ request('keyword') }}"
                    placeholder="商品名で検索"
                    class="flex-1 min-w-[200px] border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                >
                <select name="company_id" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    <option value="">すべてのメーカー</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" @selected(request('company_id') == $company->id)>
                            {{ $company->company_name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2 rounded-lg font-semibold">
                    検索
                </button>
            </form>

            <!-- 商品グリッド -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                    <a href="{{ route('shop.show', $product) }}" class="bg-white rounded-xl shadow-sm hover:shadow-lg transition overflow-hidden flex flex-col">
                        <div class="h-40 flex items-center justify-center bg-gradient-to-br from-cyan-100 to-blue-100">
                            @if($product->img_path)
                                <img src="{{ asset('storage/' . $product->img_path) }}" alt="{{ $product->product_name }}" class="h-full w-full object-cover">
                            @else
                                <span class="text-6xl">🥤</span>
                            @endif
                        </div>
                        <div class="p-4 flex-1 flex flex-col">
                            <p class="text-xs text-gray-400 mb-1">{{ $product->company->company_name ?? '-' }}</p>
                            <h3 class="font-semibold text-gray-800 mb-2">{{ $product->product_name }}</h3>
                            <div class="mt-auto flex items-center justify-between">
                                <span class="text-lg font-bold text-cyan-700">¥{{ number_format($product->price) }}</span>
                                @if($product->stock > 0)
                                    <span class="text-xs text-green-600">在庫あり</span>
                                @else
                                    <span class="text-xs text-red-500">在庫切れ</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @empty
                    <p class="col-span-full text-center text-gray-500 py-12">該当する商品がありません。</p>
                @endforelse
            </div>

            <div class="mt-8 flex justify-center">
                {{ $products->links('vendor.pagination.tailwind') }}
            </div>
        </div>
    </div>
</x-app-layout>
