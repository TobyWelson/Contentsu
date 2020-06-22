<?php
namespace Tests\Feature;

use App\Comment;
use App\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostDetailApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function should_正しい構造のJSONを返却する()
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
                'view_count' => $post->view_count,
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