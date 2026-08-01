<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="bg-gray-50 min-h-screen py-10">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('orders.index') }}" class="text-cyan-700 hover:underline text-sm">&larr; 注文履歴へ戻る</a>

            <h1 class="text-2xl font-bold text-gray-800 mt-4 mb-6">注文番号 #{{ $order->id }}</h1>

            <div class="bg-white rounded-xl shadow-sm p-6">
                <p class="text-sm text-gray-500 mb-4">{{ $order->created_at->format('Y年n月j日 H:i') }} 注文</p>

                <div class="space-y-3 mb-4">
                    @foreach($order->items as $item)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">{{ $item->product_name }} × {{ $item->quantity }}（単価 ¥{{ number_format($item->price) }}）</span>
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
                    @if($order->shipping_tel)
                        <p>電話番号：{{ $order->shipping_tel }}</p>
                    @endif
                    <p>お支払い方法：{{ $order->payment_method === 'cod' ? '代金引換' : 'クレジットカード' }}</p>
                    <p>ステータス：{{ $order->status === 'paid' ? '注文完了' : $order->status }}</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
