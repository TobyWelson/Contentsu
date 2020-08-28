<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Http\Requests\StoreComment;
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
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * 転載投稿
     * @param StorePost $request
     * @return \Illuminate\Http\Response
     */
    public function create(StorePost $request)
    {
        $post = new Post();

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
     * 転載記事削除
     * @param string $id
     * @return array
     */
    public function postDelete(string $id)
    {
        $post = Post::where('id', $id)->with(['owner', 'comments.author', 'likes'])->first();

        // データベースエラー時にロールバックする為
        // トランザクションを利用する
        DB::beginTransaction();

        try {
            $post->delete();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        return ["post_id" => $id];
    }

    /**
     * 転載記事一覧
     * @param Request $request
     * @return posts
     */
    public function index(Request $request)
    {
        $posts = null;
        $title = $request->title;
        $category = $request->category;

        if($category == '1') {
            $posts = Post::where('title','like','%'.$title.'%')
            ->with(['owner', 'likes'])
            ->orderBy(Post::CREATED_AT, 'desc')->paginate();
        } else if($category == '2') {
            $posts = Post::where('title','like','%'.$title.'%')
            ->with(['owner', 'likes'])
            ->orderBy(Post::raw('RANDOM()'))->paginate();
        } else {
            $posts = Post::where('category', $category)
            ->where('title','like','%'.$title.'%')
            ->with(['owner', 'likes'])
            ->orderBy(Post::CREATED_AT, 'desc')->paginate();
        }
        return $posts;
    }

    /**
     * 転載記事詳細
     * @param string $id
     * @return Post
     */
    public function show(string $id)
    {
        $post = Post::where('id', $id)
        ->with(['owner', 'comments.author', 'likes'])->first();
        
        // 未ログイン又はログイン中で自分の投稿以外の場合
        if (!Auth::check()
            || (Auth::check() && $post->user_id <> Auth::user()->id)) {
            $post->view_count = (int)$post->view_count + 1;
            $post->save();
        }

        return $post ?? abort(404);
    }

    /**
     * コメント投稿
     * @param Post $post
     * @param StoreComment $request
     * @return \Illuminate\Http\Response
     */
    public function addComment(Post $post, StoreComment $request)
    {
        $comment = new Comment();
        $comment->content = $request->get('content');
        $comment->user_id = Auth::user()->id;
        $post->comments()->save($comment);

        // authorリレーションをロードするためにコメントを取得しなおす
        $new_comment = Comment::where('id', $comment->id)->with('author')->first();

        return response($new_comment, 201);
    }

    /**
     * いいね
     * @param string $id
     * @return array
     */
    public function like(string $id)
    {
        $post = Post::where('id', $id)->with('likes')->first();

        if (! $post) {
            abort(404);
        }

        $post->likes()->detach(Auth::user()->id);
        $post->likes()->attach(Auth::user()->id);

        return ["post_id" => $id];
    }

    /**
     * いいね解除
     * @param string $id
     * @return array
     */
    public function unlike(string $id)
    {
        $post = Post::where('id', $id)->with('likes')->first();

        if (! $post) {
            abort(404);
        }

        $post->likes()->detach(Auth::user()->id);

        return ["post_id" => $id];
    }
}