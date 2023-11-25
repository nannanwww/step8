<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
    <title>商品情報詳細画面</title>
</head>

<body>
    <div class="wrapper">
        <h1>商品情報詳細画面</h1>
        <div class="contains">
            <form action="{{ route('products.edit', ['id' => $product->id]) }}" method="GET"
                enctype="multipart/form-data">
                @csrf
                <div class="product-details">
                    <div class="detail-row">
                        <label for="productId">ID</label>
                        <input type="text" name="productId" id="productId" value="{{ $product->id }}" readonly>
                    </div>
                    <div class="detail-row">
                        <label for="productName">商品名</label>
                        <input type="text" name="productName" id="productName" value="{{ $product->product_name }}"
                            readonly>
                    </div>
                    <div class="detail-row">
                        <label for="companyName">メーカー名</label>
                        <input type="text" name="companyName" id="companyName" value="{{ $companyName }}" readonly>
                    </div>
                    <div class="detail-row">
                        <label for="price">価格</label>
                        <input type="text" name="price" id="price" value="{{ $product->price }}" readonly>
                    </div>
                    <div class="detail-row">
                        <label for="stock">在庫数</label>
                        <input type="text" name="stock" id="stock" value="{{ $product->stock }}" readonly>
                    </div>
                    <div class="detail-row">
                        <label for="description">コメント</label>
                        <textarea type="text" name="description" id="description"
                            readonly>{{ $product->description }}</textarea>
                    </div>
                    <div class="detail-row">
                        <label for="image">商品画像</label>
                        <div class="product_image">
                            @if ($product->image)
                            <img src="{{ asset('storage/images/' . basename($product->image)) }}" alt="商品画像">
                            @else
                            <div class="spacer">No image</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="button-container">
                    <button type="button" onclick="location.href=
                '{{ route('products.edit', ['id' => $product->id]) }}'" class="submit edit">編集</button>
                    <button type="button"
                        onclick="location.href=
                '{{ route('products.index') }}?page={{ session('products_page', 1) }}&{{ session('searchParams', '') }}'"
                        class="submit back">戻る</button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>