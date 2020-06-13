<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePost;
use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        // 認証が必要
        $this->middleware('auth')->except(['index']);
    }

    /**
     * 転載投稿
     * @param StorePost $request
     * @return \Illuminate\Http\Response
     */
    public function create(StorePost $request)
    {
        $post = new Post();

        // タイトル名を設定する
        $post->title = $request->title;
        $post->category = $request->category;
        $post->url = $request->url;
        $post->view_count = 0;

        // データベースエラー時にファイル削除を行うため
        // トランザクションを利用する
        DB::beginTransaction();

        try {
            Auth::user()->posts()->save($post);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        // リソースの新規作成なので
        // レスポンスコードは201(CREATED)を返却する
        return response($post, 201);
    }

    /**
     * 転載記事一覧
     */
    public function index()
    {
        $posts = Post::with(['owner'])
            ->orderBy(Post::CREATED_AT, 'desc')->paginate();

        return $posts;
    }
    
}