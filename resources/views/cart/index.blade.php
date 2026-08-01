<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">ショッピングカート</h1>

            @if(session('success'))
                <div class="mb-4 text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-2 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($items->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-10 text-center text-gray-500">
                    カートに商品がありません。
                    <div class="mt-4">
                        <a href="{{ route('shop.index') }}" class="text-cyan-700 hover:underline">商品一覧を見る &rarr;</a>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm divide-y">
                    @foreach($items as $item)
                        <div class="flex items-center gap-4 p-5">
                            <div class="h-16 w-16 flex items-center justify-center rounded-lg bg-gradient-to-br from-cyan-100 to-blue-100 shrink-0">
                                @if($item['product']->img_path)
                                    <img src="{{ asset('storage/' . $item['product']->img_path) }}" class="h-full w-full object-cover rounded-lg">
                                @else
                                    <span class="text-3xl">🥤</span>
                                @endif
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-gray-400">{{ $item['product']->company->company_name ?? '-' }}</p>
                                <a href="{{ route('shop.show', $item['product']) }}" class="font-semibold text-gray-800 hover:text-cyan-700 truncate block">
                                    {{ $item['product']->product_name }}
                                </a>
                                <p class="text-sm text-gray-500">単価 ¥{{ number_format($item['product']->price) }}</p>
                            </div>

                            <form action="{{ route('cart.update', $item['product']) }}" method="POST" class="flex items-center gap-2">
                                @csrf
                                @method('PATCH')
                                <input
                                    type="number"
                                    name="quantity"
                                    value="{{ $item['quantity'] }}"
                                    min="0"
                                    max="{{ $item['product']->stock }}"
                                    class="w-16 border border-gray-300 rounded-lg px-2 py-1 text-center"
                                >
                                <button type="submit" class="text-sm text-cyan-700 hover:underline">更新</button>
                            </form>

                            <p class="w-24 text-right font-semibold text-gray-800">¥{{ number_format($item['subtotal']) }}</p>

                            <form action="{{ route('cart.remove', $item['product']) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-sm">削除</button>
                            </form>
                        </div>
                    @endforeach
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 mt-6 flex items-center justify-between">
                    <span class="text-gray-600">合計金額</span>
                    <span class="text-2xl font-bold text-cyan-700">¥{{ number_format($total) }}</span>
                </div>

                <div class="mt-6 flex justify-between">
                    <a href="{{ route('shop.index') }}" class="text-cyan-700 hover:underline">&larr; 買い物を続ける</a>
                    <a href="{{ route('checkout.index') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-8 py-3 rounded-lg font-semibold">
                        レジに進む
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
