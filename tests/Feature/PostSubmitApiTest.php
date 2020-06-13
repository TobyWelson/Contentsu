<?php

namespace Tests\Feature;

use App\Post;
use App\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostSubmitApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function 記事を投稿できる1()
    {
        $response = $this->actingAs($this->user)
            ->json('POST', route('post.create'), [
                'title' => '面白いyoutuber１',
                'category' => 'ゲーム実況',
                'url' => 'https://www.youtube.com/watch?v=ySQIOtjSHIc&list=RDySQIOtjSHIc&start_radio=1'
            ]);

        // レスポンスが201(CREATED)であること
        $response->assertStatus(201);

        $post = Post::first();

        // 転載記事のIDが12桁のランダムな文字列であること
        $this->assertRegExp('/^[0-9a-zA-Z-_]{12}$/', $post->id);

    }

    /**
     * @test
     */
    public function 記事を投稿できる2()
    {
        $response = $this->actingAs($this->user)
            ->json('POST', route('post.create'), [
                'title' => '面白いyoutuber１',
                'category' => 'ゲーム実況',
                'url' => 'https://youtu.be/ySQIOtjSHIc'
            ]);

        // レスポンスが201(CREATED)であること
        $response->assertStatus(201);

        $post = Post::first();

        // 転載記事のIDが12桁のランダムな文字列であること
        $this->assertRegExp('/^[0-9a-zA-Z-_]{12}$/', $post->id);

    }

    /**
     * @test
     */
    public function 記事を投稿エラーの場合はDBへの挿入はしない1()
    {
        $response = $this->actingAs($this->user)
            ->json('POST', route('post.create'), [
                'title' => '面白いyoutuber１',
                'category' => 'ゲーム実況',
                'url' => 'https://www.youtubse.com/watch?v=ySQIOtjSHIc&list=RDySQIOtjSHIc&start_radio=1'
            ]);

        // レスポンスが422(INTERNAL SERVER ERROR)であること
        $response->assertStatus(422);

        // データベースに何も挿入されていないこと
        $this->assertEmpty(Post::all());
    }

    /**
     * @test
     */
    public function 記事を投稿エラーの場合はDBへの挿入はしない2()
    {
        $response = $this->actingAs($this->user)
            ->json('POST', route('post.create'), [
                'title' => '面白いyoutuber１',
                'category' => 'ゲーム実況',
                'url' => 'https://youtu.bes'
            ]);

        // レスポンスが422(INTERNAL SERVER ERROR)であること
        $response->assertStatus(422);

        // データベースに何も挿入されていないこと
        $this->assertEmpty(Post::all());
    }

    /**
     * @test
     */
    public function 記事を投稿エラーの場合はDBへの挿入はしない3()
    {
        // $response = $this->actingAs($this->user)
        //     ->json('POST', route('post.create'), [
        //         'title' => '面白いyoutuber１',
        //         'category' => 'ゲーム実況',
        //         'url' => 'https://youtu.be/ySQIOtjSHIc'
        //     ]);

        // // レスポンスが500(INTERNAL SERVER ERROR)であること
        // $response->assertStatus(500);

        // データベースに何も挿入されていないこと
        $this->assertEmpty(Post::all());
        
    }
}