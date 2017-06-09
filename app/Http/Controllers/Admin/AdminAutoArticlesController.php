<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Article;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use App\Helpers\Helper;
use Auth;

class AdminAutoArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $input = Input::all();

        if(!empty($input)){
            $keyword = str_replace(' ', '_', $input['keyword']);
        }

        if(!empty($keyword)){
            $search_query = [
                'Operation' => 'ItemSearch',
                'ResponseGroup' => 'Medium',
                'Keywords' => $keyword,
                'SearchIndex' => 'Books',
            ];

            $amazon_response = Helper::amazonAdAPI($search_query);
            if(isset($amazon_response['Items']['Item'])){
                $get_amazon_items = $amazon_response['Items']['Item'];
            } else {
                $get_amazon_items = null;
            }

            if(!empty($get_amazon_items)){
                $title = str_replace('_', ' ', $keyword);
                $slug = str_replace(' ', '-',  strtolower($title));
                $slug_check = Article::where('slug' , $slug)->first();
                if(!empty($slug_check)){
                    $slug = $slug.'_'.Carbon::now()->timestamp;
                }
                $article = new Article();
                $article->title = "Best $title books";
                $article->user_id = Auth::user()->id;
                $article->body = "Here your will get some books of $title";
                $article->category_id = 16;
                $article->keyword = $title;
                $article->status = false;
                $article->waiting_for_approval = true;
                $article->meta_description = "Get best $title books";
                $article->slug = $slug;
                $article->save();

                foreach($get_amazon_items as $item){
                    if(isset($item['EditorialReviews']['EditorialReview'])){
                        $editorial_array = $item['EditorialReviews']['EditorialReview'];
                    }
                    $editorial_details = '';
                    if(!isset($editorial_array['Content'])){
                        foreach($editorial_array as $editorial_item){
                            $editorial_details = $editorial_item['Content'];
                        }
                    } else {
                        $editorial_details = $editorial_array['Content'];
                    }

                    if(isset($item['ItemAttributes']['Author'])){
                        $author_number = count($item['ItemAttributes']['Author']);
                        if($author_number){
                            $author_name = $item['ItemAttributes']['Author'];
                        }
                    } else {
                        $author_name = '';
                    }
                    $product = new Product();
                    $product->isbn = $item['ASIN'];
                    $product->product_title = $item['ItemAttributes']['Title'];
                    $product->product_description = $editorial_details;
                    $product->brand_id = 'amazon';
                    $product->link = $item['DetailPageURL'];
                    $product->image_url = $item['LargeImage']['URL'];
                    $product->author_id = $author_name;
                    $product->article_id = $article->id;
                    $product->save();
                }
            }
        }
        return view('admin.auto_articles.index');
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

}
