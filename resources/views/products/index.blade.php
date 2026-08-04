<x-app-layout>
    <x-slot name="header"></x-slot>
    
    <div class="bg-white min-h-screen py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- ページタイトル -->
            <h2 class="font-normal text-4xl mb-12 text-gray-800 leading-tight">
                商品一覧画面
            </h2>

            <!-- 検索フォーム -->
            <form id="searchForm" class="mb-12 flex w-full gap-2" onsubmit="return false;">

            <!-- 入力欄とセレクトボックスの共通スタイル -->
                @php
                    $formInputClass = 'border border-gray-400 rounded px-3 py-2 h-10 focus:outline-none focus:border-gray-500';
                @endphp

                <!-- キーワード検索 -->
                <input
                    type="text"
                    id="keyword"
                    name="keyword"
                    value="{{ request('keyword') }}" 
                    placeholder="検索キーワード"
                    class="{{ $formInputClass }} flex-1"
                >

                <!-- 価格範囲 -->
                <div class="flex items-center gap-1">
                    <input type="number" name="price_min" id="price_min" placeholder="価格下限" class="{{ $formInputClass }} w-24">
                    <span class="text-gray-600">〜</span>
                    <input type="number" name="price_max" id="price_max" placeholder="上限" class="{{ $formInputClass }} w-24">
                </div>

                <!-- 在庫範囲 -->
                <div class="flex items-center gap-1">
                    <input type="number" name="stock_min" id="stock_min" placeholder="在庫下限" class="{{ $formInputClass }} w-24">
                    <span class="text-gray-600">〜</span>
                    <input type="number" name="stock_max" id="stock_max" placeholder="上限" class="{{ $formInputClass }} w-24">
                </div>
                <!-- メーカー名選択 -->
                <div class="relative flex-1">
                    <select
                        name="company_id"
                        class="{{ $formInputClass }} select-custom w-full border border-gray-400 rounded px-3 py-2 pr-8 appearance-none"
                    >
                        <option value="">メーカー名</option>
                        @foreach($companies as $company)
                            <option
                                value="{{ $company->id }}" 
                                @selected(request('company_id') == $company->id)
                            >
                                {{ $company->company_name }}
                            </option>
                        @endforeach
                    </select>
                    <!-- セレクト矢印 -->
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-4">
                        ▽
                    </div>
                </div>

                <!-- 検索ボタン -->
                <button
                    type="button"
                    id="searchBtn"
                    class="{{ $formInputClass }} bg-white text-black hover:bg-gray-100"
                >
                    検索
                </button>
            </form>

            <!-- 商品テーブル -->
            <div class="bg-white overflow-hidden">
                <table id="productTable" class="table-fixed text-2xl border-collapse border border-gray-500 w-full text-left">

                    <!-- テーブルヘッダー -->
                    <thead>
                        <tr class="bg-white">
                            <th class="sortable px-5 py-5 w-8 text-center" data-column="id">ID</th>
                            <th class="px-1 py-5 w-24 text-center">商品画像</th>
                            <th class="sortable px-1 py-5 w-16 text-center" data-column="product_name">商品名</th>
                            <th class="sortable px-1 py-5 w-16 text-center" data-column="price">価格</th>
                            <th class="sortable px-1 py-5 w-16 text-center" data-column="stock">在庫数</th>
                            <th class="sortable px-1 py-5 w-24 text-center" data-column="company_name">メーカー名</th>
                            <th class="px-1 py-5 w-32 text-center">
                                <a 
                                    href="{{ route('products.create') }}"
                                    class="font-normal bg-orange-400 rounded hover:bg-orange-600 text-black text-xl border border-gray-400 px-2 py-1.5 inline-block"
                                >
                                    新規登録
                                </a>
                            </th>
                        </tr>
                    </thead>

                    <!-- テーブルボディ -->
                    <tbody>
                        @include('products.table_rows', ['products' => $products])
                    </tbody>
                </table>
            </div>

            <!-- ページネーション -->
            <div id="pagination" class="mt-6 flex flex-col items-center">
                {!! $products->links('vendor.pagination.tailwind') !!}
            </div>
        </div>
    </div>
    <script>
        const productSearchUrl = "{{ route('products.search') }}";
    </script>
</x-app-layout>