<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\InitialWeightRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class InitialWeightController extends Controller
{
    /**
     * 初期体重登録画面を表示
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('initial_weight_registration');
    }

    /**
     * 初期体重をデータベースに保存
     *
     * @param  \App\Http\Requests\InitialWeightRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(InitialWeightRequest $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->current_weight = $request->input('current_weight');
            $user->target_weight = $request->input('target_weight');
            $user->save();
            use App\Models\Weight;
            Weight::create([
                'user_id' => $user->id,
                'current_weight' => $request->input('current_weight'),
                'target_weight' => $request->input('target_weight'),
            ]);

            return redirect()->route('weight.management')->with('success', '初期体重が登録されました。');
        }

        return back()->withErrors('ユーザー情報が見つかりません。');
    }
}