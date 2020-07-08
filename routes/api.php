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
// 会員登録
Route::post('/register', 'Auth\RegisterController@register')->name('register');

// ログイン
Route::post('/login', 'Auth\LoginController@login')->name('login');

// ログアウト
Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

// ログインユーザー
Route::get('/user', fn() => Auth::user())->name('user');

// 転載記事投稿
Route::post('/posts', 'PostController@create')->name('post.create');

// 転載記事一覧
Route::get('/posts/{category}', 'PostController@index')->name('post.index');

// 転載記事詳細(認証必須)
Route::get('/posts/{id}/authshow', 'PostController@authshow')->name('post.authshow');

// 転載記事詳細(認証不要)
Route::get('/posts/{id}/show', 'PostController@show')->name('post.show');

// コメント
Route::post('/posts/{post}/comments', 'PostController@addComment')->name('post.comment');

// いいね
Route::put('/posts/{id}/like', 'PostController@like')->name('post.like');

// いいね解除
Route::delete('/posts/{id}/like', 'PostController@unlike');

// トークンリフレッシュ
Route::get('/reflesh-token', function (Illuminate\Http\Request $request) {
    $request->session()->regenerateToken();

    return response()->json();
});