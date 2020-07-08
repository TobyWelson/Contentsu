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
     * 転載記事一覧
     * @param string $category
     * @return Post
     */
    public function index(string $category)
    {
        $posts = null;
        if($category == 'ALL') {
            $posts = Post::with(['owner', 'likes'])
            ->orderBy(Post::CREATED_AT, 'desc')->paginate();
        } else {
            $posts = Post::where('category', $category)
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

        $post->view_count = (int)$post->view_count + 1;
        $post->save();

        return $post ?? abort(404);
    }

    /**
     * 転載記事詳細
     * @param string $id
     * @return Post
     */
    public function authshow(string $id)
    {
        $post = Post::where('id', $id)
        ->with(['owner', 'comments.author', 'likes'])->first();

        // 呼び出されるたびに閲覧者数がカウントアップされる
        // 正し投稿者本人の場合はカウントアップされない。
        if ($post->user_id <> Auth::user()->id) {
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