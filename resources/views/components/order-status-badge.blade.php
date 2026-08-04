@props(['status'])

@php
    $map = [
        'pending' => ['label' => '支払い確認中', 'class' => 'bg-yellow-100 text-yellow-700'],
        'paid' => ['label' => '注文完了', 'class' => 'bg-green-100 text-green-700'],
        'cancelled' => ['label' => 'キャンセル', 'class' => 'bg-gray-200 text-gray-600'],
    ];
    $badge = $map[$status] ?? ['label' => $status, 'class' => 'bg-gray-100 text-gray-600'];
@endphp

<span {{ $attributes->merge(['class' => "inline-block text-xs px-2 py-0.5 rounded-full {$badge['class']}"]) }}>
    {{ $badge['label'] }}
</span>
