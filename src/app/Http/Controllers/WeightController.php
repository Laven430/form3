<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\WeightLog;
use App\Http\Requests\WeightLogRequest;

class WeightController extends Controller
{
    /**
     * 体重管理画面を表示
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $targetWeight = $user->target_weight;
        $currentWeight = $user->current_weight;

        $diffWeight = null;
        if (is_numeric($targetWeight) && is_numeric($currentWeight)) {
            $diffWeight = $targetWeight - $currentWeight;
        }

        $query = WeightLog::where('user_id', $user->id)
                          ->orderBy('date', 'desc');

        $searchPerformed = false;
        $searchResultsCount = 0;
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
            $searchPerformed = true;
        }

        $weightLogs = $query->paginate(8);
        $searchResultsCount = $weightLogs->total();
        return view('weight_management', compact(
            'targetWeight',
            'currentWeight',
            'diffWeight',
            'weightLogs',
            'searchPerformed',
            'searchResultsCount',
            'startDate',
            'endDate'
        ));
    }

    /**
     * 体重ログをデータベースに保存 (モーダルからの登録)
     *
     * @param  \App\Http\Requests\WeightLogRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeLog(WeightLogRequest $request)
    {
        $user = Auth::user();

        $existingLog = WeightLog::where('user_id', $user->id)
                                ->where('date', $request->date)
                                ->first();

        if ($existingLog) {
            $existingLog->update([
                'weight' => $request->weight,
                'calories_intake' => $request->calories_intake,
                'exercise_time' => $request->exercise_time,
                'exercise_content' => $request->exercise_content,
            ]);
            $user->current_weight = $request->weight;
            $user->save();

            return redirect()->route('weight.management')->with('success', '本日の体重記録を更新しました。');
        } else {
            WeightLog::create([
                'user_id' => $user->id,
                'date' => $request->date,
                'weight' => $request->weight,
                'calories_intake' => $request->calories_intake,
                'exercise_time' => $request->exercise_time,
                'exercise_content' => $request->exercise_content,
            ]);
            $user->current_weight = $request->weight;
            $user->save();

            return redirect()->route('weight.management')->with('success', '新しい体重記録を追加しました。');
        }
    }
    public function editLog(WeightLog $weightLog)
    {
        if (Auth::id() !== $weightLog->user_id) {
            abort(403);
        }
        return view('weight_log_edit', compact('weightLog'));
    }

    public function updateLog(WeightLogRequest $request, WeightLog $weightLog)
    {
        if (Auth::id() !== $weightLog->user_id) {
            abort(403);
        }

        $weightLog->update([
            'date' => $request->date,
            'weight' => $request->weight,
            'calories_intake' => $request->calories_intake,
            'exercise_time' => $request->exercise_time,
            'exercise_content' => $request->exercise_content,
        ]);

        $latestLog = WeightLog::where('user_id', Auth::id())
                                ->orderBy('date', 'desc')
                                ->first();
        if ($latestLog && $latestLog->id === $weightLog->id) {
            $user = Auth::user();
            $user->current_weight = $weightLog->weight;
            $user->save();
        }elseif ($latestLog && $weightLog->date > $latestLog->date) {
            $user = Auth::user();
            $user->current_weight = $weightLog->weight;
            $user->save();
        }

        return redirect()->route('weight.management')->with('success', '体重記録を更新しました。');
    }

    /**
     * 体重ログを削除する (情報更新画面からの「ゴミ箱」ボタン)
     *
     * @param  \App\Models\WeightLog  $weightLog
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroyLog(WeightLog $weightLog)
    {
        if (Auth::id() !== $weightLog->user_id) {
            abort(403, '許可されていない操作です。');
        }

        $weightLog->delete();

        $user = Auth::user();
        $latestLog = WeightLog::where('user_id', $user->id)
                                ->orderBy('date', 'desc')
                                ->first();
        $user->current_weight = $latestLog ? $latestLog->weight : null;
        $user->save();

        return redirect()->route('weight.management')->with('success', '体重記録を削除しました。');
    }
}