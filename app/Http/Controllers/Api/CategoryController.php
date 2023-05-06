<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Validator;

class CategoryController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $categories = Category::all();

        return response()->json([
            "success" => true,
            "message" => "Category List",
            "data" => $categories
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
            'category_name' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['Validation Error.', $validator->errors()], 422);      
        }

        $category = Category::create($input);

        return response()->json([
            "success" => true,
            "message" => "Category created successfully.",
            "data" => $category
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
        $category = Category::find($id);

        if (is_null($category)) {
            return response()->json(['Category not found.']);
        }

        return response()->json([
            "success" => true,
            "message" => "Category retrieved successfully.",
            "data" => $category
        ]);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Category $category)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'category_name' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['Validation Error.', $validator->errors()], 422);     
        }

        $category->category_name = $input['category_name'];
        $category->save();

        return response()->json([
            "success" => true,
            "message" => "Category updated successfully.",
            "data" => $category
        ]);
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            "success" => true,
            "message" => "Category deleted successfully.",
            "data" => $category
        ]);
    }
}
