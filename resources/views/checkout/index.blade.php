<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">ご購入手続き</h1>

            @if(session('error'))
                <div class="mb-4 text-red-700 bg-red-50 border border-red-200 rounded-lg px-4 py-2 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <div class="md:flex gap-8 items-start">
                <form action="{{ route('checkout.store') }}" method="POST" class="bg-white rounded-xl shadow-sm p-6 flex-1 space-y-4">
                    @csrf

                    <h2 class="font-semibold text-gray-700 mb-2">お届け先情報</h2>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">お名前</label>
                        <input type="text" name="shipping_name" value="{{ old('shipping_name') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        @error('shipping_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">郵便番号</label>
                        <input type="text" name="shipping_zip" value="{{ old('shipping_zip') }}" placeholder="123-4567"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">住所</label>
                        <input type="text" name="shipping_address" value="{{ old('shipping_address') }}" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                        @error('shipping_address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">電話番号</label>
                        <input type="text" name="shipping_tel" value="{{ old('shipping_tel') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                    </div>

                    <h2 class="font-semibold text-gray-700 pt-4 mb-2">お支払い方法</h2>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2">
                            <input type="radio" name="payment_method" value="credit_card" checked class="text-cyan-600">
                            クレジットカード（デモ）
                        </label>
                        <label class="flex items-center gap-2">
                            <input type="radio" name="payment_method" value="cod" class="text-cyan-600">
                            代金引換
                        </label>
                    </div>

                    <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-3 rounded-lg font-semibold mt-4">
                        注文を確定する
                    </button>
                </form>

                <div class="bg-white rounded-xl shadow-sm p-6 w-full md:w-80 mt-6 md:mt-0 shrink-0">
                    <h2 class="font-semibold text-gray-700 mb-4">ご注文内容</h2>
                    <div class="space-y-3 mb-4">
                        @foreach($items as $item)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 truncate pr-2">{{ $item['product']->product_name }} × {{ $item['quantity'] }}</span>
                                <span class="text-gray-800 whitespace-nowrap">¥{{ number_format($item['subtotal']) }}</span>
                            </div>
                        @endforeach
                    </div>
                    <div class="border-t pt-4 flex justify-between font-bold text-cyan-700">
                        <span>合計</span>
                        <span>¥{{ number_format($total) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
