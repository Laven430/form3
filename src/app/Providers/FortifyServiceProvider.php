<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Route; // もしFortify::routes()をここで使うなら必要
use Laravel\Fortify\Fortify;
use Illuminate\Support\ServiceProvider; // これが正しい基底クラス

use App\Providers\RouteServiceProvider; // HomeController::class などで使う場合

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(
            \Laravel\Fortify\Contracts\CreatesNewUsers::class,
            CreateNewUser::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // ★ここが最も重要です！Fortifyの認証ルートをここで定義します。★
        Fortify::routes();

        // ログインビューのカスタマイズ
        Fortify::loginView(function () {
            return view('auth.login');
        });

        // 登録ビューのカスタマイズ（もしカスタムビューを使うなら）
        Fortify::registerView(function () {
            return view('auth.register');
        });


        // ログイン後のリダイレクト先
        Fortify::homeRoute(RouteServiceProvider::HOME); // RouteServiceProvider::HOME で定義されたパスへ

        // 登録後のリダイレクト先
        Fortify::redirects('register', '/initial-weight-registration'); // 登録後、初期体重登録画面へ


        // バリデーションルールのカスタマイズ
        Validator::extend('custom_email_format', function ($attribute, $value, $parameters, $validator) {
            return preg_match('/^.+@.+\..+$/', $value);
        });

        \Laravel\Fortify\Fortify::createUsersUsing(CreateNewUser::class);

        Fortify::authenticateUsing(function (Request $request) {
            Validator::make($request->all(), [
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ], [
                'email.required' => 'メールアドレスを入力してください',
                'email.email' => 'メールアドレスは「ユーザー名@ドメイン」形式で入力してください',
                'password.required' => 'パスワードを入力してください',
            ])->validate();
            if (Fortify::attempt($request)) {
                return $request->user();
            }
            return null;
        });

        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(50)->by($request->input('email').$request->ip());
        });
    }
}