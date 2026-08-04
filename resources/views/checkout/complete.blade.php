<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="bg-gray-50 min-h-screen py-16">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="text-6xl mb-4">🎉</div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">ご注文ありがとうございました！</h1>
            <p class="text-gray-500 mb-8">注文番号 #{{ $order->id }} を承りました。</p>

            <div class="bg-white rounded-xl shadow-sm p-6 text-left">
                <h2 class="font-semibold text-gray-700 mb-4">ご注文内容</h2>
                <div class="space-y-3 mb-4">
                    @foreach($order->items as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $item->product_name }} × {{ $item->quantity }}</span>
                            <span class="text-gray-800">¥{{ number_format($item->subtotal) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="border-t pt-4 flex justify-between font-bold text-cyan-700 mb-4">
                    <span>合計</span>
                    <span>¥{{ number_format($order->total_price) }}</span>
                </div>
                <div class="border-t pt-4 text-sm text-gray-600 space-y-1">
                    <p>お届け先：{{ $order->shipping_name }} 様</p>
                    <p>{{ $order->shipping_zip }} {{ $order->shipping_address }}</p>
                    <p>お支払い方法：{{ $order->payment_method === 'cod' ? '代金引換' : 'クレジットカード' }}</p>
                    <p class="flex items-center gap-2">ステータス：<x-order-status-badge :status="$order->status" /></p>
                </div>
            </div>

            <div class="mt-8 flex justify-center gap-4">
                <a href="{{ route('shop.index') }}" class="text-cyan-700 hover:underline">買い物を続ける</a>
                <a href="{{ route('orders.index') }}" class="bg-cyan-600 hover:bg-cyan-700 text-white px-6 py-2 rounded-lg font-semibold">
                    注文履歴を見る
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
