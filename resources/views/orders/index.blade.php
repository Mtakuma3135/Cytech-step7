<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">注文履歴</h1>

            @if($orders->isEmpty())
                <div class="bg-white rounded-xl shadow-sm p-10 text-center text-gray-500">
                    まだ注文がありません。
                    <div class="mt-4">
                        <a href="{{ route('shop.index') }}" class="text-cyan-700 hover:underline">商品一覧を見る &rarr;</a>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-xl shadow-sm divide-y">
                    @foreach($orders as $order)
                        <a href="{{ route('orders.show', $order) }}" class="flex items-center justify-between p-5 hover:bg-gray-50">
                            <div>
                                <p class="font-semibold text-gray-800">注文番号 #{{ $order->id }}</p>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('Y年n月j日 H:i') }}</p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-cyan-700">¥{{ number_format($order->total_price) }}</p>
                                <x-order-status-badge :status="$order->status" />
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-6 flex justify-center">
                    {{ $orders->links('vendor.pagination.tailwind') }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
