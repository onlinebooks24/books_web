<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Article;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();
        $articles = Article::where('status', true)->orderBy('created_at','desc')->Paginate(10);
        return view('frontend.articles',['categories'=>$categories,'articles'=>$articles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getSinglePost($slug)
    {
        $articles = Article::orderBy('created_at','desc')->Paginate(10);
        $categories = Category::all();
        $article = Article::where('slug' , $slug)->first();
        return view('frontend.single',['article'=>$article, 'articles' => $articles, 'categories' => $categories]);
    }

    public function getCategoryPost($slug)
    {
        $categories = Category::all();
        $category = Category::where('slug' , $slug)->first();
        if (!empty($category)){
            $articles = Article::where('category_id' , $category->id)->orderBy('created_at','desc')->Paginate(5);
            return view('frontend.category',['articles'=> $articles,'categories' => $categories,'categoryName' => $category->name ]);
        } else {
            return redirect()->route('blog.index');
        }
    }
}
