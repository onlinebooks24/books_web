<?php

namespace App\Http\Controllers;

use App\Models\EmailSubscriber;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Article;
use App\Models\Product;
use App\Models\ProductOrder;
use Auth;
use App\Models\Upload;
use DB;
use Mail;

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
        $articles = Article::where('status', true)->orderBy('created_at','desc')->Paginate(27);

        $popular_articles = Article::where('status', true)->orderBy('count','desc')->Paginate(25);

        $related_articles = Article::whereIn('id', [153,81,109])->orderBy('id', 'asc')
            ->get();

        $uploads = Upload::all();
        return view('frontend.articles.index',
                ['categories' => $categories,
                'articles' => $articles,
                'uploads' => $uploads,
                'related_articles' => $related_articles,
                'popular_articles' => $popular_articles
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
    public function show($slug, Request $request)
    {
        if(empty($request['email'])){
            $articles = Article::where('status', true)->orderBy('created_at','asc')->Paginate(18);
            $categories = Category::where('category_status', true)
                ->orderBy('created_at','desc')->get();
            $article = Article::where('slug' , $slug)->first();

            if(!isset($article)){
                return redirect(route('blog.index'));
            }

            $popular_articles = Article::where('status', true)->orderBy('count','desc')->Paginate(25);

            $ordered_product_articles = ProductOrder::select('product_number', 'title')
                                        ->where('article_id', $article->id)
                                        ->where('manually_inserted_on_article', true)
                                        ->distinct('product_number')->get();

            $related_articles = Article::where('status', true) ->orderBy(DB::raw('RAND()'))
                ->take(3)
                ->get();

            $products = Product::where('article_id',$article->id)->orderBy('created_at','asc')->get();
            $uploads = Upload::all();
            if(empty(Auth::user())){
                $current_count = $article->count;
                $article->count = $current_count + 1 ;
                $article->save();
            }

            if($request['format'] == 'json'){
                $json_data['article'] = $article;
                $json_data['products'] = $article->products;
                return response($json_data);
            }

            return view('frontend.articles.show',[ 'article'=> $article,
                'articles' => $articles,
                'categories' => $categories,
                'products' => $products,
                'uploads' => $uploads,
                'related_articles' => $related_articles,
                'ordered_product_articles' => $ordered_product_articles,
                'popular_articles' => $popular_articles]);
        } else {
            setcookie("email", $request['email'], 2147483647);
            $email_subscriber = EmailSubscriber::where('email', $request['email'])->first();

            if(!empty($email_subscriber)){
                $email_subscriber->subscribe = true;
                $email_subscriber->temporary = false;
                $email_subscriber->click_count += 1;
                $email_subscriber->save();
            }

            return redirect()->to(url()->current());
        }

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
            $popular_articles = Article::where('status', true)->orderBy('count','desc')->Paginate(25);

            return view('frontend.articles.category_articles',
                    ['articles'=> $articles,
                    'categories' => $categories,
                    'category' => $category,
                    'uploads' => $uploads,
                    'popular_articles' => $popular_articles]);
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
