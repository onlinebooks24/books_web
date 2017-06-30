<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Article;
use Auth;
use App\Models\Upload;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('category_status', true)
            ->orderBy('created_at','desc')->get();
        $articles = Article::where('status', true)->orderBy('created_at','desc')->Paginate(18);
        $uploads = Upload::all();
        return view('frontend.articles.index',['categories'=>$categories,'articles'=>$articles,'uploads'=>$uploads]);
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
        $articles = Article::where('status', true)->orderBy('created_at','desc')->Paginate(18);
        $categories = Category::where('category_status', true)
            ->orderBy('created_at','desc')->get();
        $article = Article::where('slug' , $slug)->first();
        $products = $article->products;
        $uploads = Upload::all();
        if(empty(Auth::user())){
            $current_count = $article->count;
            $article->count = $current_count + 1 ;
            $article->save();
        }

        return view('frontend.articles.show',[ 'article'=>$article,
                                        'articles' => $articles,
                                        'categories' => $categories,
                                        'products' => $products,
                                         'uploads' => $uploads]);
    }

    public function getCategoryPost($slug)
    {
        $categories = Category::where('category_status', true)
            ->orderBy('created_at','desc')->get();
        $category = Category::where('slug' , $slug)->first();
        $uploads = Upload::all();
        if (!empty($category)){
            $articles = Article::where('category_id' , $category->id)
                                    ->where('status', true)
                                    ->orderBy('created_at','desc')->Paginate(5);
            return view('frontend.articles.category_articles',['articles'=> $articles,'categories' => $categories,'categoryName' => $category->name,'uploads' => $uploads ]);
        } else {
            return redirect()->route('blog.index');
        }
    }
}
