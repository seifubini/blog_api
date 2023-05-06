<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;

class CategoryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_display_all_categories(): void
    {
        $response = $this->get('/api/categories');

        $response->assertStatus(200);
    }

    public function test_create_category()
    {
        $data = [
            'category_name' => 'News'
        ];

        $this->post('/api/categories/', $data)
            ->assertStatus(200);
    }

    public function test_fetch_category()
    {
        $category = Category::create(['category_name' => 'Jazz']);

        $this->json('GET', 'api/categories/' . $category->id, [], ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_update_category()
    {
        $category = Category::create(['category_name' => 'Musics']);

        $new_cat = ['category_name' => 'Pop Musics'];

        $this->json('PATCH', 'api/categories/' . $category->id , $new_cat, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    public function test_delete_category()
    {
        $category = Category::create([
            "category_name" => "Football"
        ]);

        $this->json('DELETE', 'api/categories/' . $category->id, [], ['Accept' => 'application/json'])
            ->assertStatus(200);
    }
}
