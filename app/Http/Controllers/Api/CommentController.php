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

class CommentController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index($id)
    {
        $comments = DB::table('comments')
            ->Join('posts', 'posts.id', '=', 'comments.post_id')
            ->Join('images', 'images.comment_id', '=', 'comments.id')
            ->select('comments.*', 'images.image_url')
            ->where('posts.id', $id)->get();

        return response()->json([
            "success" => true,
            "message" => "Comments List",
            "data" => $comments
        ]);
    }

    public function store(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'comment' => 'required|string', 
            'images.*' => 'required|image|mimes:jpeg,png,jpg,svg|max:1024',
        ]);

        if($validator->fails()){
            return response()->json(['Validation Error.', $validator->errors()], 422);
        }

        $comment = new Comment; 

        $comment->comment = $input['comment']; 
        $comment->post_id = $id; 

        $comment->save();

        $comment_id = $comment->id;

        $num = count($input['images']);
        $comment_pictures = $input['images'];

        for($j=0; $j<$num; $j++)
        {
            $image = $comment_pictures[$j];
            $imageName = 'img_'.$j.time().'.'.$image->extension();
            $image->move(public_path('images/comments'), $imageName);
            
            $imgs = new Image;

            $imgs->comment_id = $comment_id;
            $imgs->image_url = $imageName;

            $imgs->save();
        }

        return response()->json([
            "success" => true,
            "message" => "Comment created successfully.",
            "data" => $comment
        ]);
    }

    public function show($id)
    {
        $comment = DB::table('comments')
            ->Join('images', 'images.comment_id', '=', 'comments.id')
            ->select('comments.*', 'images.image_url')
            ->where('comments.id', $id)->first();

        if (is_null($comment)) {
            return response()->json(['Comment not found.']);
        }

        return response()->json([
            "success" => true,
            "message" => "Comment retrieved successfully.",
            "data" => $comment
        ]);
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'comment' => 'required|string', 
            'images.*' => 'required|image|mimes:jpeg,png,jpg,svg|max:1024',
        ]);

        if($validator->fails()){
            return response()->json(['Validation Error.', $validator->errors()], 422);
        }

        $comment = Comment::find($id);

        $comment->comment = $input['comment']; 
        $comment->post_id = $id; 

        $comment->save();

        $comment_id = $comment->id;

        $num = count($input['images']);
        $comment_pictures = $input['images'];

        for($j=0; $j<$num; $j++)
        {
            $image = $comment_pictures[$j];
            $imageName = 'img_'.$j.time().'.'.$image->extension();
            $image->move(public_path('images/comments'), $imageName);
            
            $imgs = new Image;

            $imgs->comment_id = $comment_id;
            $imgs->image_url = $imageName;

            $imgs->save();
        }

        return response()->json([
            "success" => true,
            "message" => "Comment updated successfully.",
            "data" => $comment
        ]);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);

        Comment::where('id', $id)->delete();

        return response()->json([
            "success" => true,
            "message" => "Comment deleted successfully.",
            "data" => $comment
        ]);
    }
}
