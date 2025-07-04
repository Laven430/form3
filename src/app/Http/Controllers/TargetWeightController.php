<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Requests\TargetWeightRequest;

class TargetWeightController extends Controller
{
    /**
     * 目標体重変更画面を表示します。
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $user = Auth::user();
        $targetWeight = $user->target_weight;

        return view('target_weight_settings', compact('targetWeight'));
    }

    /**
     * 目標体重を更新します。
     *
     * @param  \App\Http\Requests\TargetWeightRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(TargetWeightRequest $request)
    {
        $user = Auth::user();
        $user->target_weight = $request->target_weight;
        $user->save();
        return redirect()->route('weight.management')->with('success', '目標体重を更新しました。');
    }
}