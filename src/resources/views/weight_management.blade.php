<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>体重管理画面</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .error-message {
            color: red;
            font-size: 0.8em;
            margin-top: 5px;
        }

        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .hover-effect:hover {
            background-color: #f0f0f0;
            cursor: pointer;
        }
        .edit-icon {
            display: inline-block;
            width: 20px;
            height: 20px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpath d='M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z'%3E%3C/path%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center center;
            background-size: contain;
            vertical-align: middle;
            margin-left: 5px;
        }
    </style>
</head>
<body class="bg-gray-100 p-6 font-sans">
    <div class="container mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">体重管理画面</h1>

        <div class="flex justify-around items-center mb-8 bg-blue-50 p-4 rounded-lg">
            <div class="text-center">
                <p class="text-lg text-gray-600">目標体重</p>
                <p class="text-2xl font-semibold text-blue-700">{{ $targetWeight ?? '未設定' }} kg</p>
            </div>
            <div class="text-center">
                <p class="text-lg text-gray-600">現在体重</p>
                <p class="text-2xl font-semibold text-green-700">{{ $currentWeight ?? '未設定' }} kg</p>
            </div>
            <div class="text-center">
                <p class="text-lg text-gray-600">目標まで</p>
                @if (is_numeric($diffWeight))
                    <p class="text-2xl font-semibold {{ $diffWeight > 0 ? 'text-red-600' : 'text-green-600' }}">
                        {{ sprintf('%.1f', $diffWeight) }} kg
                    </p>
                @else
                    <p class="text-2xl font-semibold text-gray-500">計算不可</p>
                @endif
            </div>
        </div>

        <div class="flex justify-between mb-8">
            <button onclick="location.href='{{ route('target.weight.settings') }}'" class="px-5 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition">目標体重設定</button>
            <button onclick="document.getElementById('add-data-modal').classList.remove('hidden')" class="px-5 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition">データを追加</button>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-5 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition">ログアウト</button>
            </form>
        </div>

        <div class="mb-8 p-4 bg-gray-50 rounded-lg shadow-sm">
            <h2 class="text-xl font-semibold text-gray-700 mb-4">検索</h2>
            <form action="{{ route('weight.management') }}" method="GET" class="flex flex-col md:flex-row items-center space-y-4 md:space-y-0 md:space-x-4">
                <div class="flex-grow w-full md:w-auto">
                    <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">開始日:</label>
                    <input type="date" id="start_date" name="start_date" value="{{ request('start_date') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                </div>
                <div class="flex-grow w-full md:w-auto">
                    <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">終了日:</label>
                    <input type="date" id="end_date" name="end_date" value="{{ request('end_date') }}" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm p-2">
                </div>
                <div class="md:self-end w-full md:w-auto">
                    <button type="submit" class="w-full md:w-auto px-5 py-2 bg-gray-700 text-white rounded-md hover:bg-gray-800 transition">検索</button>
                </div>
                @if($searchPerformed)
                    <div class="md:self-end w-full md:w-auto">
                        <button type="button" onclick="location.href='{{ route('weight.management') }}'" class="w-full md:w-auto px-5 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500 transition">リセット</button>
                    </div>
                @endif
            </form>
        </div>

        @if($searchPerformed)
            <div class="mb-6 text-lg font-semibold text-gray-700">
                {{ \Carbon\Carbon::parse($startDate)->format('Y/m/d') }} 〜 {{ \Carbon\Carbon::parse($endDate)->format('Y/m/d') }} の検索結果 {{ $searchResultsCount }} 件
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                <thead>
                    <tr class="bg-gray-100 text-left text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">日付</th>
                        <th class="py-3 px-6 text-left">体重</th>
                        <th class="py-3 px-6 text-left">摂取カロリー</th>
                        <th class="py-3 px-6 text-left">運動時間</th>
                        <th class="py-3 px-6 text-left">運動内容</th>
                        <th class="py-3 px-6 text-left"></th> </tr>
                </thead>
                <tbody class="text-gray-700 text-sm font-light">
                    @forelse($weightLogs as $log)
                        <tr class="border-b border-gray-200 hover:bg-gray-50 hover-effect">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $log->date->format('Y/m/d') }}</td>
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ sprintf('%.1f', $log->weight) }}kg</td>
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $log->calories_intake ?? '---' }} kcal</td>
                            <td class="py-3 px-6 text-left whitespace-nowrap">
                                {{ $log->exercise_time ? \Carbon\Carbon::parse($log->exercise_time)->format('H:i') : '---' }}
                            </td>
                            <td class="py-3 px-6 text-left">{{ $log->exercise_content ?? '---' }}</td>
                            <td class="py-3 px-6 text-left">
                                <a href="{{ route('weight.log.edit', $log->id) }}" class="text-blue-500 hover:text-blue-700">
                                    <span class="edit-icon"></span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-4 px-6 text-center text-gray-500">まだ体重記録がありません。</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">
            {{ $weightLogs->links() }}
        </div>
    </div>

    <div id="add-data-modal" class="modal-overlay hidden">
        <div class="modal-content">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">体重記録の追加/更新</h2>
            <form method="POST" action="{{ route('weight.log.store') }}">
                @csrf
                <div>
                    <label for="log_date" class="block text-gray-700 text-sm font-bold mb-2">日付:</label>
                    <input type="date" id="log_date" name="date" value="{{ old('date', \Carbon\Carbon::now()->toDateString()) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('date')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mt-4">
                    <label for="log_weight" class="block text-gray-700 text-sm font-bold mb-2">体重 (kg):</label>
                    <input type="number" step="0.1" id="log_weight" name="weight" value="{{ old('weight') }}" placeholder="例: 65.5" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('weight')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mt-4">
                    <label for="calories_intake" class="block text-gray-700 text-sm font-bold mb-2">摂取カロリー (kcal):</label>
                    <input type="number" id="calories_intake" name="calories_intake" value="{{ old('calories_intake') }}" placeholder="例: 2000" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('calories_intake')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mt-4">
                    <label for="exercise_time" class="block text-gray-700 text-sm font-bold mb-2">運動時間:</label>
                    <input type="time" id="exercise_time" name="exercise_time" value="{{ old('exercise_time') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    @error('exercise_time')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mt-4">
                    <label for="exercise_content" class="block text-gray-700 text-sm font-bold mb-2">運動内容:</label>
                    <textarea id="exercise_content" name="exercise_content" rows="3" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="例: ランニング30分">{{ old('exercise_content') }}</textarea>
                    @error('exercise_content')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="flex items-center justify-end mt-6 space-x-4">
                    <button type="button" onclick="document.getElementById('add-data-modal').classList.add('hidden')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">戻る</button>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">登録</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        @if ($errors->any())
            document.getElementById('add-data-modal').classList.remove('hidden');
        @endif
    </script>
</body>
</html>