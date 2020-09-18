<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
*/
// 仮会員登録
Route::post('/pre-register', 'Auth\RegisterController@preRegister');

// 会員登録
Route::post('/register', 'Auth\RegisterController@register')->name('register');

// 退会
Route::delete('/withdrawaler', 'Auth\WithdrawalerController@withdrawaler');

// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');

// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// ログインユーザー
Route::get('/user', fn() => Auth::user())->name('user');

// 転載記事投稿
Route::post('/posts', 'PostController@create')->name('post.create');

// 転載記事削除
Route::delete('/posts/{id}', 'PostController@postDelete')->name('post.postDelete');

// 転載記事一覧
Route::post('/index', 'PostController@index')->name('post.index');

// 転載記事詳細
Route::get('/posts/{id}/show', 'PostController@show')->name('post.show');

// コメント
Route::post('/posts/{post}/comments', 'PostController@addComment')->name('post.comment');

// コメント削除
Route::delete('/posts/{post}/comments/{id}', 'PostController@deleteComment');

// いいね
Route::put('/posts/{id}/like', 'PostController@like')->name('post.like');

// いいね解除
Route::delete('/posts/{id}/like', 'PostController@unlike');

// トークンリフレッシュ
Route::get('/reflesh-token', function (Illuminate\Http\Request $request) {
    $request->session()->regenerateToken();

    return response()->json();
});