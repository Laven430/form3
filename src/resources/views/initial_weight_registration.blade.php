<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>初期体重登録</title>
    <style>
        .error-message {
            color: red;
            font-size: 0.8em;
            margin-top: 5px;
        }
        body {
            font-family: sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
        form div {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }
        input[type="number"] {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>初期体重登録</h1>

        <form method="POST" action="{{ route('initial_weight_registration.store') }}">
            @csrf

            <div>
                <label for="current_weight">現在の体重</label>
                <input id="current_weight" type="number" step="0.1" name="current_weight" value="{{ old('current_weight') }}" placeholder="例: 65.5">
                @error('current_weight')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <label for="target_weight">目標の体重</label>
                <input id="target_weight" type="number" step="0.1" name="target_weight" value="{{ old('target_weight') }}" placeholder="例: 60.0">
                @error('target_weight')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div>
                <button type="submit">アカウント作成</button>
            </div>
        </form>
    </div>
</body>
</html>