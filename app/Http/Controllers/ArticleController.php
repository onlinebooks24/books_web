<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Article;
use App\Models\Product;
use Auth;
use App\Models\Upload;
use DB;

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
        $articles = Article::where('status', true)->orderBy('created_at','desc')->Paginate(25);

        $related_articles = Article::whereIn('id', [153,81,109])->orderBy('id', 'asc')
            ->get();

        $uploads = Upload::all();
        return view('frontend.articles.index',
                ['categories' => $categories,
                'articles' => $articles,
                'uploads' => $uploads,
                'related_articles' => $related_articles
                ]);
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
    public function show($slug)
    {
        $articles = Article::where('status', true)->orderBy('created_at','asc')->Paginate(18);
        $categories = Category::where('category_status', true)
            ->orderBy('created_at','desc')->get();
        $article = Article::where('slug' , $slug)->first();

        $related_articles = Article::where('status', true) ->orderBy(DB::raw('RAND()'))
            ->take(3)
            ->get();

        if(!isset($article)){
            return redirect(route('blog.index'));
        }
        $products = Product::where('article_id',$article->id)->orderBy('created_at','asc')->get();
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
            'uploads' => $uploads,
            'related_articles' => $related_articles]);
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

    public function CategoryJson($parent_id){
        $categories = Category::where('parent_id', $parent_id)->get();

        $temp = [];
        $category_json = null;

        foreach($categories as $category){
            $temp['id'] = $category->id;
            $temp['name'] = $category->name;
            $temp['browse_node_id'] = $category->browse_node_id;
            $category_json[] = $temp;
        }

        return response()->json($category_json);
    }
}
