<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">

            <a href="{{ route('shop.index') }}" class="text-cyan-700 hover:underline text-sm">&larr; 商品一覧へ戻る</a>

            <div class="bg-white rounded-2xl shadow-sm mt-4 overflow-hidden md:flex">
                <div class="md:w-1/2 h-72 md:h-auto flex items-center justify-center bg-gradient-to-br from-cyan-100 to-blue-100">
                    @if($product->img_path)
                        <img src="{{ asset('storage/' . $product->img_path) }}" alt="{{ $product->product_name }}" class="h-full w-full object-cover">
                    @else
                        <span class="text-9xl">🥤</span>
                    @endif
                </div>

                <div class="md:w-1/2 p-8 flex flex-col">
                    <p class="text-sm text-gray-400 mb-1">{{ $product->company->company_name ?? '-' }}</p>
                    <h1 class="text-2xl font-bold text-gray-800 mb-4">{{ $product->product_name }}</h1>
                    <p class="text-3xl font-bold text-cyan-700 mb-4">¥{{ number_format($product->price) }}</p>

                    @if($product->comment)
                        <p class="text-gray-600 mb-4 leading-relaxed">{{ $product->comment }}</p>
                    @endif

                    <p class="text-sm mb-6">
                        @if($product->stock > 0)
                            <span class="text-green-600">在庫あり（残り{{ $product->stock }}点）</span>
                        @else
                            <span class="text-red-500">在庫切れ</span>
                        @endif
                    </p>

                    @if(session('success'))
                        <div class="mb-4 text-green-700 bg-green-50 border border-green-200 rounded-lg px-4 py-2 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($product->stock > 0)
                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex items-end gap-3">
                            @csrf
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">数量</label>
                                <input
                                    type="number"
                                    name="quantity"
                                    value="1"
                                    min="1"
                                    max="{{ $product->stock }}"
                                    class="w-20 border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400"
                                >
                            </div>
                            <button type="submit" class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2 rounded-lg font-semibold">
                                カートに入れる
                            </button>
                        </form>
                    @else
                        <button disabled class="bg-gray-300 text-gray-500 px-6 py-2 rounded-lg font-semibold w-fit cursor-not-allowed">
                            在庫切れ
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
