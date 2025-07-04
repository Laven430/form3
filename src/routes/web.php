<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WeightController;
use App\Http\Controllers\TargetWeightController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\RegisterController; // 会員登録コントローラ
use App\Http\Controllers\InitialWeightController; // 初期体重登録コントローラ

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// ウェルカムページ
Route::get('/', function () {
    return view('welcome');
});

// 認証済みユーザー向けのダッシュボード（必要であれば）
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// 会員登録画面（ステップ1を想定）
// Fortifyが提供する登録フォームを使う場合は、Fortify::registerViewで設定するため、
// このルートは不要になることが多いですが、もしカスタムビューを使うなら残します。
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');


// 初期体重登録画面（会員登録後の遷移先）
Route::get('/initial-weight-registration', [InitialWeightController::class, 'create'])->name('initial_weight_registration');
Route::post('/initial-weight-registration', [InitialWeightController::class, 'store'])->name('initial_weight_registration.store');


// 認証済みユーザーのみアクセスできるルートグループ
Route::middleware('auth')->group(function () {
    // 体重管理画面
    Route::get('/weight-management', [WeightController::class, 'index'])->name('weight.management');
    // 体重ログの保存（モーダルからの登録/更新）
    Route::post('/weight-management/log', [WeightController::class, 'storeLog'])->name('weight.log.store');
    // 体重ログの編集画面表示
    Route::get('/weight-management/log/{weightLog}/edit', [WeightController::class, 'editLog'])->name('weight.log.edit');
    // 体重ログの更新処理
    Route::put('/weight-management/log/{weightLog}', [WeightController::class, 'updateLog'])->name('weight.log.update');
    // 体重ログの削除処理
    Route::delete('/weight-management/log/{weightLog}', [WeightController::class, 'destroyLog'])->name('weight.log.destroy');

    // 目標体重変更画面
    Route::get('/target-weight-settings', [TargetWeightController::class, 'index'])->name('target.weight.settings');
    // 目標体重の更新処理
    Route::post('/target-weight-settings', [TargetWeightController::class, 'update'])->name('target.weight.update');


    // ログアウト
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// 注意: Fortify::routes() は routes/web.php から削除し、
// app/Providers/FortifyServiceProvider.php の boot() メソッド内で呼び出すべきです。
// 例: Fortify::routes();