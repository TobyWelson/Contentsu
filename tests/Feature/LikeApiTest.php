<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikeApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();

        factory(Post::class)->create();
        $this->post = Post::first();
    }

    /**
     * @test
     */
    public function should_いいねを追加できる()
    {
        $response = $this->actingAs($this->user)
            ->json('PUT', route('post.like', [
                'id' => $this->post->id,
            ]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'post_id' => $this->post->id,
            ]);

        $this->assertEquals(1, $this->post->likes()->count());
    }

    /**
     * @test
     */
    public function should_2回同じ写真にいいねしても1個しかいいねがつかない()
    {
        $param = ['id' => $this->post->id];
        $this->actingAs($this->user)->json('PUT', route('post.like', $param));
        $this->actingAs($this->user)->json('PUT', route('post.like', $param));

        $this->assertEquals(1, $this->post->likes()->count());
    }

    /**
     * @test
     */
    public function should_いいねを解除できる()
    {
        $this->post->likes()->attach($this->user->id);

        $response = $this->actingAs($this->user)
            ->json('DELETE', route('post.like', [
                'id' => $this->post->id,
            ]));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'post_id' => $this->post->id,
            ]);

        $this->assertEquals(0, $this->post->likes()->count());
    }
}