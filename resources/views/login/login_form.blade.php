<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザーログイン画面</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>

<body>
    <form class="form-sign-in" method="POST" action="{{route('login')}}">
        @csrf
        <h1 class="h3 mb-3 font-weight-normal">ユーザーログイン画面</h1>

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
        <input type="email" id="inputEmail" class="form-control" placeholder="アドレス" name="email">


        <div class="button-container">
            <button class="submit form-submit" type="submit">ログイン</button>
            <a href="{{ route('showRegistrationForm') }}" class="submit form-new">新規登録</a>
        </div>
    </form>
</body>

</html>