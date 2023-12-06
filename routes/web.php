<?php

use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

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

// 新規登録ページの表示
Route::get('/registration', [RegistrationController::class, 'index']);
// 仮登録メール送信ページの表示
Route::post('/registration', [RegistrationController::class, 'send']);
// 認証コード確認画面の表示
Route::get('/registration/check', [RegistrationController::class, 'check']);
// 登録完了画面の表示
Route::post('/registration/comp', [RegistrationController::class, 'comp']);