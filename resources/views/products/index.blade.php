<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <title>ホーム</title>
</head>

<body>
    <div class="contains">
        <form class="search" method="GET" id="searchForm" action="{{ route('products.index') }}">
            @csrf
            <h1>商品一覧画面</h1>
            <div>
                <input type="text" id="key_word" class="key-word" placeholder="検索キーワード" name="key_word"
                    value="{{ old('key_word', request()->input('key_word')) }}">
                <select type="text" id="key_company" class="key-company" name="key_company">
                    <option value="" selected disabled hidden>メーカー名を選択</option>
                    <option value="">すべて</option>
                    @foreach($companies as $companyId => $companyName)
                    <option value="{{ $companyId }}" @if($companyId==request()->input('key_company')) selected @endif>{{
                        $companyName }}</option>
                    @endforeach
                </select>
                <button type="submit" id="search_button">検索</button>
            </div>
        </form>

        <div id="products_container">
            <table id="products_table">
                <tr>
                    <form class="sort" action="{{ route('products.index') }}">
                        <input type="hidden" id="productsIndexRoute" value="{{ route('products.index') }}">
                        <th>ID</th>
                        <th>商品画像</th>
                        <th><a href="{{ route('products.index', array_merge(request()->query(), ['sort' => '1', 'order' => 'asc'])) }}"
                                class="sort_button" data-value="1">商品名</a></th>
                        <th><label for="price_flag" class="limit_button">価格</label>
                            <input type="checkbox" id="price_flag" class="limit_check">
                            <div class="limit_popup">
                                <form class="priceSort" method="GET" id="priceSort"
                                    action="{{ route('products.index') }}">
                                    <input type="number" name="minPrice" class="min-box-Price" placeholder="最小値"
                                        value="{{ old('minPrice', request()->input('minPrice')) }}">
                                    <span>~</span>
                                    <input type="number" name="maxPrice" class="max-box-Price" placeholder="最大値"
                                        value="{{ old('maxPrice', request()->input('maxPrice')) }}" max="9999">
                                    <input type="submit" href="javascript:void(0)" class="sort_button price-sort"
                                        data-value="3" value=">>">
                                </form>
                            </div>
                        </th>
                        <th><label for="stock_flag" class="limit_button">在庫</label>
                            <input type="checkbox" id="stock_flag" class="limit_check">
                            <div class="stock_popup">
                                <form class="stockSort" method="GET" id="stockSort"
                                    action="{{ route('products.index') }}">
                                    @csrf
                                    <input type="number" name="minStock" class="min-box-Stock" placeholder="最小値"
                                        value="{{ old('minStock', request()->input('minStock')) }}">
                                    <span>~</span>
                                    <input type="number" name="maxStock" class="max-box-Stock" placeholder="最大値"
                                        value="{{ old('maxStock', request()->input('maxStock')) }}" max="9999">
                                    <input type="submit" href="#" class="sort_button stock-sort" data-value="4"
                                        value=">>">
                                </form>
                            </div>
                        </th>
                        <th><a href="{{ route('products.index', array_merge(request()->query(), ['sort' => '2', 'order' => 'asc'])) }}"
                                class="sort_button" data-value="2">メーカー名</a></th>
                        <th><a href="{{ route('products.create') }}">
                                <button type="button" class="create">新規登録</button>
                            </a>
                        </th>
                    </form>
                </tr>
                @foreach ($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>
                        @if ($product->image)
                        <img src="{{ asset('storage/images/' . basename($product->image)) }}" alt="商品画像">
                        @else
                        No image
                        @endif
                    </td>
                    <td>{{ $product->product_name }}</td>
                    <td>{{ $product->price }} 円</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $companies[$product->company_id] }}</td>
                    <td>
                        <div class="btn-contains">
                            <button onclick="saveSearchParamsAndRedirect('{{ json_encode(request()->query()) }}', '{{ route('products.showDetail',
                            ['id' => $product->id]) }}')" class="detail">詳細</button>
                            <form action="{{ route('products.delete', ['id' => $product->id]) }}" method="POST"
                                class="delete-form">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" class="product-id" value="{{ $product->id }}">
                                <button type="submit" class="delete-btn" id="delete">削除</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </table>

            <div class="pagination">
                @if ($products->lastPage() > 1)
                @if ($products->onFirstPage())
                <span class="disabled">＜</span>
                @else
                @php
                $previousPageParams = $products->currentPage() - 1;
                $previousPageUrl = url()->current() . '?' . http_build_query(array_merge(request()->query(),
                ['page' => $previousPageParams]));
                @endphp
                <span><a href="{{ $previousPageUrl }}">＜</a></span>
                @endif

                @for ($i = 1; $i <= $products->lastPage(); $i++)
                    @php
                    $queryParams = request()->query();
                    $queryParams['page'] = $i;
                    $queryString = http_build_query($queryParams);
                    $url = url()->current() . '?' . $queryString;
                    @endphp
                    <span @if ($i==$products->currentPage()) class="active" @endif><a href="{{ $url }}">{{ $i
                            }}</a></span>
                    @endfor

                    @if ($products->hasMorePages())
                    @php
                    $nextPageParams = $products->currentPage() + 1;
                    $nextPageUrl = url()->current() . '?' . http_build_query(array_merge(request()->query(), ['page' =>
                    $nextPageParams]));
                    @endphp
                    <span><a href="{{ $nextPageUrl }}">＞</a></span>
                    @else
                    <span class="disabled">＞</span>
                    @endif
                    @endif
            </div>
        </div>

        @if ($errorMessage)
        <div class="alert alert-danger">
            {{ $errorMessage }}
        </div>
        @endif
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/index.js') }}"></script>
</body>

</html>