<?php
namespace Tests\Feature;

use App\Comment;
use App\Post;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostDetailApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_正しい構造のJSONを返却する_認証なし()
    {
        factory(Post::class)->create()->each(function ($post) {
            $post->comments()->saveMany(factory(Comment::class, 3)->make());
        });
        $post = Post::first();

        $response = $this->json('GET', route('post.show', [
            'id' => $post->id,
        ]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $post->id,
                'title' => $post->title,
                'url' => $post->url,
                'category' => $post->category,
                'view_count' => 1,
                'owner' => [
                    'name' => $post->owner->name,
                ],
                'comments' => $post->comments
                    ->sortByDesc('id')
                    ->map(function ($comment) {
                        return [
                            'author' => [
                                'name' => $comment->author->name,
                            ],
                            'content' => $comment->content,
                        ];
                    })
                    ->all(),
            ]);
    }

    /**
     * @test
     */
    public function should_正しい構造のJSONを返却する_認証あり同一ユーザー()
    {

        $this->user = factory(User::class)->create();

        factory(Post::class)->create()->each(function ($post) {
            $post->comments()->saveMany(factory(Comment::class, 3)->make());
        });
        $post = Post::first();

        $post->user_id = $this->user->id;
        $post->save();

        $response = $this->actingAs($this->user)
        ->json('GET', route('post.show', [
            'id' => $post->id,
        ]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $post->id,
                'title' => $post->title,
                'url' => $post->url,
                'category' => $post->category,
                'view_count' => '0',
                'owner' => [
                    'name' => $post->owner->name,
                ],
                'comments' => $post->comments
                    ->sortByDesc('id')
                    ->map(function ($comment) {
                        return [
                            'author' => [
                                'name' => $comment->author->name,
                            ],
                            'content' => $comment->content,
                        ];
                    })
                    ->all(),
            ]);
    }

    /**
     * @test
     */
    public function should_正しい構造のJSONを返却する_認証あり別ユーザー()
    {

        $this->user = factory(User::class)->create();

        factory(Post::class)->create()->each(function ($post) {
            $post->comments()->saveMany(factory(Comment::class, 3)->make());
        });
        $post = Post::first();

        $post->user_id = 5;
        $post->save();
        
        $response = $this->actingAs($this->user)
        ->json('GET', route('post.show', [
            'id' => $post->id,
        ]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $post->id,
                'title' => $post->title,
                'url' => $post->url,
                'category' => $post->category,
                'view_count' => 1,
                'owner' => [
                    'name' => $post->owner->name,
                ],
                'comments' => $post->comments
                    ->sortByDesc('id')
                    ->map(function ($comment) {
                        return [
                            'author' => [
                                'name' => $comment->author->name,
                            ],
                            'content' => $comment->content,
                        ];
                    })
                    ->all(),
            ]);
    }
}