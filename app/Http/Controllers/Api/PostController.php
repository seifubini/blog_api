<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Post;
use App\Models\Image;
use App\Models\Comment;
use Validator;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $posts = DB::table('posts')
            ->leftJoin('images', 'images.post_id', '=', 'posts.id')
            ->leftJoin('comments', 'comments.post_id', '=', 'posts.id')
            ->select('posts.*', 'comments.comment', 'images.image_url')->get();

        return response()->json([
            "success" => true,
            "message" => "Posts List",
            "data" => $posts
        ]);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required|string', 
            'content' => 'required|string', 
            'category_id' => 'required|string',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,svg|max:1024',
        ]);

        if($validator->fails()){
            return response()->json(['Validation Error.', $validator->errors()], 422);
        }

        $post = new Post; 

        $post->title = $input['title']; 
        $post->content = $input['content']; 
        $post->category_id = $input['category_id'];

        $post->save();

        $post_id = $post->id;

        $num = count($input['images']);
        $post_pictures = $input['images'];

        for($j=0; $j<$num; $j++)
        {
            $image = $post_pictures[$j];
            $imageName = $input['title'].$j.time().'.'.$image->extension();
            $image->move(public_path('images/posts'), $imageName);
            
            $imgs = new Image;

            $imgs->post_id = $post_id;
            $imgs->image_url = $imageName;

            $imgs->save();
        }

        return response()->json([
            "success" => true,
            "message" => "Post created successfully.",
            "data" => $post
        ]);
    } 

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $post = DB::table('posts')
            ->leftJoin('images', 'images.post_id', '=', 'posts.id')
            ->leftJoin('comments', 'comments.post_id', '=', 'posts.id')
            ->select('posts.*', 'comments.comment', 'images.image_url')
            ->where('posts.id', $id)->first();

        if (is_null($post)) {
            return response()->json(['Post not found.']);
        }

        return response()->json([
            "success" => true,
            "message" => "Post retrieved successfully.",
            "data" => $post
        ]);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Post $post)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'title' => 'required|string', 
            'content' => 'required|string', 
            'category_id' => 'required|string',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,svg|max:1024',
        ]);

        if($validator->fails()){
            return response()->json(['Validation Error.', $validator->errors()], 422);
        }

        $post->title = $input['title']; 
        $post->content = $input['content']; 
        $post->category_id = $input['category_id'];

        $post->save();

        $post_id = $post->id;

        $num = count($input['images']);
        $post_pictures = $input['images'];

        for($j=0; $j<$num; $j++)
        {
            $image = $post_pictures[$j];
            $imageName = $input['title'].$j.time().'.'.$image->extension();
            $image->move(public_path('images/posts'), $imageName);
            
            $imgs = new Image;

            $imgs->post_id = $post_id;
            $imgs->image_url = $imageName;

            $imgs->save();
        }

        return response()->json([
            "success" => true,
            "message" => "Post updated successfully.",
            "data" => $post
        ]);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(Post $post)
    {
        $post->delete();

        return response()->json([
            "success" => true,
            "message" => "Post deleted successfully.",
            "data" => $post
        ]);
    }
}
