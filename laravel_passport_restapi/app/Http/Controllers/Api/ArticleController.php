<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Article;
use Validator;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all();
        return response()->json([
            "success" => true,
            "message" => "Article List",
            "data" => $articles
        ]);
    }

    public function store(Request $request)
    {
        $userid = Auth::guard('api')->user()->id;

        $input = [
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'user_id' => $userid,
            'image' => $request->file('image')->getClientOriginalName()
        ];
        $validator = Validator::make($input, [
            'title' => 'required',
            'content' => 'required',
            'image' => 'required',
            'category_id' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                "success" => false,
                "message" => $validator->errors()
            ]);       
        }
        $file = $request->file('image');
        $path = public_path() . '/uploads/images/store/';
        $file->move($path, $file->getClientOriginalName());
        $article = Article::create($input);
        return response()->json([
            "success" => true,
            "message" => "Article created successfully.",
            "data" => $article
        ]);
    } 

    public function show($id)
    {
        $article = Article::find($id);
        if (is_null($article)) {
            return response()->json([
                "success" => false,
                "message" => 'Article not found'
            ]); 
        }
        return response()->json([
            "success" => true,
            "message" => "Article retrieved successfully.",
            "data" => $article
        ]);
    }

    public function update(Request $request, Article $article)
    {
        $input = [
            'title' => $request->title,
            'content' => $request->content,
            'category_id' => $request->category_id
        ];
        $validator = Validator::make($input, [
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required'
        ]);
        if($validator->fails()){
            return response()->json([
                "success" => false,
                "message" => $validator->errors()
            ]); 
        }
        $article->title = $input['title'];
        $article->content = $input['content'];
        $article->category_id = $input['category_id'];
        $article->save();
        return response()->json([
            "success" => true,
            "message" => "Article updated successfully.",
            "data" => $article
        ]);
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return response()->json([
            "success" => true,
            "message" => "Article deleted successfully.",
            "data" => $article
        ]);
    }
}
