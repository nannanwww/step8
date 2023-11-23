<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
    <title>商品情報の編集</title>
</head>

<body>
    <div class="wrapper">
        <h1>商品情報の編集</h1>
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="containts">
            <form id="editForm" action="{{ route('products.update', $product->id) }}" method="post"
                enctype="multipart/form-data">
                @method('PUT')
                @csrf
                <div class="product-details">
                    <div class="detail-row">
                        <label for="productId">ID</label>
                        <input type="text" name="productId" id="productId" value="{{ $product->id }}" readonly>
                    </div>
                    <div class="detail-row">
                        <label for="">商品名<span>*</span></label>
                        <input type="text" name="product_name" id="product_name" value="{{$product->product_name}}">
                    </div>
                    <div class="detail-row">
                        <label for="">メーカー名<span>*</span></label>
                        <input type="text" name="company" id="company" value="{{$product->company}}">
                    </div>
                    <div class="detail-row">
                        <label for="">価格<span>*</span></label>
                        <input type="text" name="price" id="price" value="{{$product->price}}">
                    </div>
                    <div class="detail-row">
                        <label for="">在庫数<span>*</span></label>
                        <input type="text" name="stock" id="stock" value="{{$product->stock}}">
                    </div>
                    <div class="detail-row">
                        <label for="">コメント</label>
                        <textarea name="description" id="description">{{$product->description}}</textarea>
                    </div>
                    <div class="detail-row">
                        <label for="image">商品画像</label>
                        <div class="image-input-container">
                            @if ($product->image)
                            <img src="{{ asset('storage/images/' . basename($product->image)) }}" alt="商品画像"
                                class="product_image">
                            @else
                            <div class="spacer">
                                No image
                            </div>
                            @endif
                            <input type="file" name="image" id="image" accept="image/*" value="{{$product->image}}"
                                class="input-file">
                        </div>
                    </div>

                    <div class="button-container">
                        <button type="submit" class="submit update">更新</button>
                        <button type="button"
                            onclick="location.href='{{ route('products.showDetail', ['id' => $product->id]) }}'"
                            class="submit back">戻る</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/edit.js') }}"></script>
</body>

</html>