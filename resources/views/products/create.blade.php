<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
    <title>商品情報登録画面</title>
</head>

<body>
    <div class="wrapper">
        <h1>商品情報登録画面</h1>
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <!-- エラーと成功はアラートに -->
        <div class="contains">
            <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="product-details">
                    <div class="detail-row">
                        <label for="inputProduct_name">商品名<span>*</span></label>
                        <input type="text" name="product_name" id="inputProduct_name" value="{{ old('product_name') }}"
                            required>
                    </div>
                    <div class="detail-row">
                        <label for="inputCompany">メーカー名<span>*</span></label>
                        <select name="company_name" required>
                            <option value="">会社名を選択してください</option>
                            @foreach($companies as $id => $company)
                            <option value="{{ $company }}">{{ $company }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="detail-row">
                        <label for="inputPrice">価格<span>*</span></label>
                        <input type="text" name="price" id="inputPrice" title="数字を入力してください" value="{{ old('price') }}"
                            required>
                    </div>
                    <div class="detail-row">
                        <label for="inputStock">在庫数<span>*</span></label>
                        <input type="text" name="stock" id="inputStock" title="数字を入力してください" value="{{ old('stock') }}"
                            required>
                    </div>
                    <div class="detail-row">
                        <label for="inputDescription">コメント</label>
                        <textarea name="description" id="inputDescription">{{ old('description') }}</textarea>
                    </div>
                    <div class="detail-row">
                        <label for="inputImage">商品画像</label>
                        <input type="file" name="image" id="inputImage" accept="image/*" class="image-input-container">
                    </div>
                </div>
                <div class="button-container">
                    <button type="submit" class="submit form-submit">新規登録</button>
                    <button type="button" onclick="location.href='{{ URL::previous() }}'"
                        class="submit back">戻る</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/create.js') }}"></script>
</body>

</html>