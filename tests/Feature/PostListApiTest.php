<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PostListApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_正しい構造のJSONを返却する()
    {
        // 5つの記事データを生成する
        factory(Post::class, 5)->create();

        $response = $this->json('GET', route('post.index'));

        // 生成した写真データを作成日降順で取得
        $posts = Post::with(['owner'])->orderBy('created_at', 'desc')->get();

        // data項目の期待値
        $expected_data = $posts->map(function ($post) {
            return [
                'category' => $post->category,
                'created_at' => $post->created_at,
                'id' => $post->id,
                'owner' => [
                    'name' => $post->owner->name,
                ],
                'title' => $post->title,
                'updated_at' => $post->updated_at,
                'url' => $post->url,
                'user_id' => $post->user_id,
                'view_count' => $post->view_count,
            ];
        })
        ->all();

        $response->assertStatus(200)
            // レスポンスJSONのdata項目に含まれる要素が5つであること
            ->assertJsonCount(5, 'data')
            // レスポンスJSONのdata項目が期待値と合致すること
            ->assertJsonFragment([
                "data" => $expected_data,
            ]);
    }
}