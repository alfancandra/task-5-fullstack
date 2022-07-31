<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Category;
use Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();
        return response()->json([
            "success" => true,
            "message" => "Category List",
            "data" => $category
        ]);
    }

    public function store(Request $request)
    {
        $userid = Auth::guard('api')->user()->id;

        $input = [
            'name' => $request->name,
            'user_id' => $userid
        ];
        $validator = Validator::make($input, [
            'name' => 'required',
        ]);
        if($validator->fails()){
            return response()->json([
                "success" => false,
                "message" => $validator->errors()
            ]);       
        }
        $category = Category::create($input);
        return response()->json([
            "success" => true,
            "message" => "Category created successfully.",
            "data" => $category
        ]);
    } 

    public function show($id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return response()->json([
                "success" => false,
                "message" => 'Category not found'
            ]); 
        }
        return response()->json([
            "success" => true,
            "message" => "Category retrieved successfully.",
            "data" => $category
        ]);
    }

    public function update(Request $request, Category $category)
    {
        $input = [
            'name' => $request->name
        ];
        $validator = Validator::make($input, [
            'name' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                "success" => false,
                "message" => $validator->errors()
            ]); 
        }
        $category->name = $input['name'];
        $category->save();
        return response()->json([
            "success" => true,
            "message" => "Category updated successfully.",
            "data" => $category
        ]);
    }

    public function destroy(Category $category)
    {
        $categories = $category->articles->count();
        if($categories >= 1){
            return response()->json([
                "success" => true,
                "message" => "Cannot delete Category, because it is used in Article."
            ]);
        }else{
            $category->delete();
            return response()->json([
                "success" => true,
                "message" => "Category deleted successfully.",
                "data" => $category
            ]);
        }
    }
}
