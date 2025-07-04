<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>目標体重設定</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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
            font-size: 1.875rem;
            font-weight: 700;
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
            box-shadow: inset 0 1px 2px rgba(0,0,0,0.07);
            outline: none;
        }
        input[type="number"]:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 20px;
        }
        .button-group button {
            flex-grow: 1;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .button-group .back-button {
            background-color: #6c757d;
            color: white;
        }
        .button-group .back-button:hover {
            background-color: #5a6268;
        }

        .button-group .update-button {
            background-color: #007bff;
            color: white;
        }
        .button-group .update-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>目標体重設定</h1>

        <form method="POST" action="{{ route('target.weight.update') }}">
            @csrf

            <div>
                <label for="target_weight">目標体重 (kg)</label>
                <input type="number" step="0.1" id="target_weight" name="target_weight"
                       value="{{ old('target_weight', $targetWeight) }}"
                       placeholder="例: 60.5"
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('target_weight')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="button-group">
                <button type="button" onclick="location.href='{{ route('weight.management') }}'" class="back-button">戻る</button>
                <button type="submit" class="update-button">更新</button>
            </div>
        </form>
    </div>
</body>
</html>