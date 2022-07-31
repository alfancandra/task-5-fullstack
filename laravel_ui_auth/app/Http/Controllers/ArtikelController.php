<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ArtikelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $data = Article::join('categories','articles.category_id','=','categories.id')
        ->select('articles.id','articles.title','articles.content','categories.name')
        ->get();
        // dd($data);
        return view('article.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user_id = Auth::user()->id;
        $category = Category::where('user_id',$user_id)->get();
        return view('article.add',compact('category'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'image' => 'required',
            'category_id' => 'required',
        ]);

        $user_id = Auth::user()->id;
        $input = $request->all();
        $input['user_id'] = $user_id;

        // Store Image
        $imageName = time().'.'.$request->image->extension();  
        $request->image->move(public_path('images'), $imageName);
        
        $input['image'] = $imageName;

        Article::create($input);

        return redirect('article');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_id = Auth::user()->id;
        $data = Article::join('categories','articles.category_id','=','categories.id')
        ->where('articles.id',$id)
        ->select('articles.id','articles.title','articles.content','articles.image','categories.name','categories.id as category_id')
        ->first();
        $category = Category::where('user_id',$user_id)->get();
        return view('article.show',compact('data','category'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user_id = Auth::user()->id;
        $data = Article::join('categories','articles.category_id','=','categories.id')
        ->where('articles.id',$id)
        ->select('articles.id','articles.title','articles.content','categories.name','categories.id as category_id')
        ->first();
        $category = Category::where('user_id',$user_id)->get();
        return view('article.edit',compact('data','category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $this->validate($request, [
            'title' => 'required',
            'content' => 'required',
            'category_id' => 'required',
        ]);

        $input = $request->all();

        if ($request->file('image')!=null){
            // Store Image
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('images'), $imageName);
            $input['image'] = $imageName;
        }

        $article->fill($input)->save();

        return redirect('article');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Article::destroy($id);
        return redirect('article');
    }
}
