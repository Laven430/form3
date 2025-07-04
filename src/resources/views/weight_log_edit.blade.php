<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>体重記録の編集</title>
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
            max-width: 500px;
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
        input[type="date"],
        input[type="number"],
        input[type="time"],
        textarea {
            width: calc(100% - 20px);
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
        .button-group button,
        .button-group a {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            font-size: 1.1em;
            cursor: pointer;
            transition: background-color 0.3s ease;
            text-decoration: none;
            text-align: center;
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
        .links {
            text-align: center;
            margin-top: 25px;
        }
        .links a, .links form button {
            color: #007bff;
            text-decoration: none;
            font-size: 0.9em;
            display: inline-block;
            margin: 0 10px;
            border: none;
            background: none;
            cursor: pointer;
            padding: 0;
            transition: text-decoration 0.3s ease;
        }
        .links a:hover, .links form button:hover {
            text-decoration: underline;
        }
        .delete-button {
            background-color: #dc3545;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 0.9em;
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            align-items: center;
        }
        .delete-button:hover {
            background-color: #c82333;
        }
        .trash-icon {
            display: inline-block;
            width: 16px;
            height: 16px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white' stroke='white' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='3 6 5 6 21 6'%3E%3C/polyline%3E%3Cpath d='M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2'%3E%3C/path%3E%3Cline x1='10' y1='11' x2='10' y2='17'%3E%3C/line%3E%3Cline x1='14' y1='11' x2='14' y2='17'%3E%3C/line%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: contain;
            margin-right: 5px;
        }
    </style>
</head>
<body class="bg-gray-100 p-6 font-sans">
    <div class="container mx-auto bg-white p-8 rounded-lg shadow-md max-w-lg">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">体重記録の編集</h1>

        <form action="{{ route('weight.log.destroy', $weightLog->id) }}" method="POST" onsubmit="return confirm('この記録を本当に削除しますか？');">
            @csrf
            @method('DELETE')
            <button type="submit" class="delete-button">
                <span class="trash-icon"></span> 削除
            </button>
        </form>

        <form method="POST" action="{{ route('weight.log.update', $weightLog->id) }}">
            @csrf
            @method('PUT') <div>
                <label for="log_date" class="block text-gray-700 text-sm font-bold mb-2">日付:</label>
                <input type="date" id="log_date" name="date" value="{{ old('date', $weightLog->date->toDateString()) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('date')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="mt-4">
                <label for="log_weight" class="block text-gray-700 text-sm font-bold mb-2">体重 (kg):</label>
                <input type="number" step="0.1" id="log_weight" name="weight" value="{{ old('weight', $weightLog->weight) }}" placeholder="例: 65.5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('weight')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="mt-4">
                <label for="calories_intake" class="block text-gray-700 text-sm font-bold mb-2">摂取カロリー (kcal):</label>
                <input type="number" id="calories_intake" name="calories_intake" value="{{ old('calories_intake', $weightLog->calories_intake) }}" placeholder="例: 2000" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('calories_intake')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="mt-4">
                <label for="exercise_time" class="block text-gray-700 text-sm font-bold mb-2">運動時間:</label>
                <input type="time" id="exercise_time" name="exercise_time" value="{{ old('exercise_time', $weightLog->exercise_time ? \Carbon\Carbon::parse($weightLog->exercise_time)->format('H:i') : '') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                @error('exercise_time')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>
            <div class="mt-4">
                <label for="exercise_content" class="block text-gray-700 text-sm font-bold mb-2">運動内容:</label>
                <textarea id="exercise_content" name="exercise_content" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="例: ランニング30分">{{ old('exercise_content', $weightLog->exercise_content) }}</textarea>
                @error('exercise_content')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex items-center justify-end mt-6 space-x-4">
                <button type="button" onclick="location.href='{{ route('weight.management') }}'" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">戻る</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">更新</button>
            </div>
        </form>

        <div class="links">
            <a href="{{ route('target.weight.settings') }}">目標体重設定</a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit">ログアウト</button>
            </form>
        </div>
    </div>
</body>
</html>