<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザー新規登録画面</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
<form class="form-signin" method="POST" action="{{ route('register') }}">
    @csrf
    <h1 class="h3 mb-3 font-weight-normal">ユーザー新規登録画面</h1>

    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <input type="password" id="inputPassword" class="form-control" placeholder="パスワード" name="password">
    <input type="password" id="inputPasswordConfirmation" class="form-control" placeholder="パスワード確認" name="password_confirmation">
    <input type="email" id="inputEmail" class="form-control" placeholder="アドレス" name="email">

    <div class="button-container">
        <button class="submit form-submit" type="submit">新規登録</button>
        <a href="{{ route('showLogin') }}" class="submit form-new">戻る</a>
    </div>
</form>
</body>
</html>