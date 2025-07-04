<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録</title>
    <style>
        .error-message {
            color: red;
            font-size: 0.8em;
        }
    </style>
</head>
<body>
    <h1>会員登録</h1>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div>
            <label for="name">名前</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
            @error('name')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="email">メールアドレス</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">
            @error('email')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password">パスワード</label>
            <input id="password" type="password" name="password" required autocomplete="new-password">
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password_confirmation">パスワード（確認用）</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>

        <div>
            <button type="submit">次に進む</button>
        </div>
    </form>

    <p>
        <a href="{{ route('login') }}">ログインはこちら</a>
    </p>
    <p>
        <a href="{{ route('initial_weight_registration') }}">次に進む（初期体重登録画面へ）</a>
    </p>
</body>
</html>