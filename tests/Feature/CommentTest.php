<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\Post;
use App\Models\Image;
use App\Models\Comment;

class CommentTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_display_all_comments(): void
    {
        $post_id = '1';

        $response = $this->get('/api/comments/'. $post_id);

        $response->assertStatus(200);
    }

    public function test_create_comment()
    {
        $post_id = '1';

        $response = $this->json('POST', '/api/comments/store/'. $post_id, [
            'comment' => 'test post', 
            'images' => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpg'),
            ],
        ]);
        $response->assertStatus(200);
    }

    public function test_fetch_comment()
    {
        $post_id = '1';

        $comment = Comment::create([
            'comment' => 'test post', 
            'post_id' => $post_id,
            'images' => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpg'),
            ],
        ]);

        $this->json('GET', 'api/comments/show/' . $comment->id, [], ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_update_comment()
    {
        $post_id = '1';

        $comment = Comment::create([
            'comment' => 'test post', 
            'post_id' => $post_id,
            'images' => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpg'),
            ],
        ]);

        $new_comment = [
            'comment' => 'another test post', 
            'post_id' => $post_id,
            'images' => [
                UploadedFile::fake()->image('image4.jpg'),
                UploadedFile::fake()->image('image4.jpg'),
            ],
        ];

        $this->json('PATCH', 'api/comments/update/' . $comment->id , $new_comment, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_delete_comment()
    {
        $post_id = '1';

        $comment = Comment::create([
            'comment' => 'test post', 
            'post_id' => $post_id,
            'images' => [
                UploadedFile::fake()->image('image1.jpg'),
                UploadedFile::fake()->image('image2.jpg'),
            ],
        ]);

        $this->json('DELETE', 'api/comments/delete/' . $comment->id, [], ['Accept' => 'application/json'])
            ->assertStatus(200);
    }
}
