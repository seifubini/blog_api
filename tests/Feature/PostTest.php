<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\Post;
use App\Models\Image;
use App\Models\Comment;

class PostTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_display_all_posts(): void
    {
        $response = $this->get('/api/posts');

        $response->assertStatus(200);
    }

    public function test_create_post()
    {
        $category_id = '2';

        $response = $this->json('POST', '/api/posts', [
            'title' => 'test post', 
            'content' => 'post content tests.', 
            'category_id' => $category_id,
            'images' => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpg'),
            ],
        ]);
        $response->assertStatus(200);
    }

    public function test_fetch_post()
    {
        $category_id = '3';

        $post = Post::create([
            'title' => 'test post', 
            'content' => 'post content tests.', 
            'category_id' => $category_id,
            'images' => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpg'),
            ],
        ]);

        $this->json('GET', 'api/posts/' . $post->id, [], ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_update_post()
    {
        $category_id = '3';

        $post = Post::create([
            'title' => 'test post', 
            'content' => 'post content tests.', 
            'category_id' => $category_id,
            'images' => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpg'),
            ],
        ]);

        $new_post = [
            'title' => 'new post', 
            'content' => 'new post content tests.', 
            'category_id' => $category_id,
            'images' => [
                UploadedFile::fake()->image('image3.jpg'),
                UploadedFile::fake()->image('image4.jpg'),
            ],
        ];

        $this->json('PATCH', 'api/posts/' . $post->id , $new_post, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_delete_post()
    {
        $category_id = '3';

        $post = Post::create([
            'title' => 'test post', 
            'content' => 'post content tests.', 
            'category_id' => $category_id,
            'images' => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpg'),
            ],
        ]);

        $this->json('DELETE', 'api/posts/' . $post->id, [], ['Accept' => 'application/json'])
            ->assertStatus(200);
    }
}
