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
use Goutte\Client;

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
        $best_books = null;

        if(!empty($input)){
            $keyword = str_replace(' ', '_', $input['keyword']);
        }

        if(!empty($keyword)){
            \Log::info("--------------Starting--------\n");

            $title = str_replace('_', ' ', $keyword);
            $slug = str_replace(' ', '-',  strtolower($title));
            $slug_check = Article::where('slug' , $slug)->first();
            if(!empty($slug_check)){
                $slug = $slug.'_'.Carbon::now()->timestamp;
            }
            $article = new Article();
            $article->title = $title;
            $article->user_id = Auth::user()->id;
            $article->body = "Here your will get some books of $title";
            $article->category_id = 16;
            $article->keyword = $title;
            $article->status = false;
            $article->waiting_for_approval = true;
            $article->meta_description = "Get best $title books";
            $article->slug = $slug;
            $article->save();

            for($i = 1; $i <= 3; $i++){
                $search_query = [
                    'Operation' => 'ItemSearch',
                    'ResponseGroup' => 'Medium',
                    'Keywords' => $keyword,
                    'SearchIndex' => 'Books',
                    'ItemPage' => $i
                ];

                $amazon_response = Helper::amazonAdAPI($search_query);
                if(isset($amazon_response['Items']['Item'])){
                    $get_amazon_items = $amazon_response['Items']['Item'];
                } else {
                    $get_amazon_items = null;
                }

                if(!empty($get_amazon_items)){

                    foreach($get_amazon_items as $item){
                        if(isset($item['ASIN'])){
                            $asin = $item['ASIN'];
                            $best_books = $this->getRankingFromAmazonReview($best_books, $asin);
                        }
                    }
                }
                sleep(2);
            }

            $client = new Client();
            $total_suggested_books = null;
            $google_keyword = 'best+' . str_replace(' ', '+',  strtolower($title));

            \Log::info("--------------$google_keyword--------\n");

            for($i=0; $i <= 1; $i++){
                $search_google = "https://www.google.com/search?q=$google_keyword&ie=utf-8&oe=utf-8&client=firefox-b&start=$i";
                \Log::info("--------------$search_google--------\n");

                $crawler = $client->request('GET', $search_google , ['verify' => false]);
                $total_suggested_books =   $crawler->filter('.r a')->each(function ($node) {
                    $link_string = $node->attr('href');
                    $link_string = str_replace('/url?q=', '', $link_string);
                    $link_string = substr($link_string, 0, strpos($link_string, "&sa="));
                    $pathinfo = pathinfo($link_string);
//                    $domain_name = parse_url($link_string)['host'];
                    if( !isset($pathinfo['extension']) && strpos($link_string, '.amazon.') == false){
                        $next_client = new Client();

                        \Log::info("--------------$link_string--------\n");
                        $next_crawler = $next_client->request('GET', $link_string, ['verify' => false]);
                        $collect_books = $next_crawler->filter('body a')->each(function ($next_node) {
                            $general_link_string = $next_node->attr('href');

                            if (strpos($general_link_string, '.amazon.') !== false) {
                                \Log::info("--------------$general_link_string--------\n");
                                if (preg_match("/\/[^\/]{10}\//", $general_link_string. "/", $matches)) {
                                    if(isset($matches[0])){
                                        return str_replace('/', '',$matches[0]);
                                    }
                                }
                            }
                        });
                        $suggest_books = null;
                        foreach( array_filter($collect_books) as $book_item){
                            $book_item = (string)$book_item;
                            if(isset($suggest_books[$book_item])){
                                $suggest_books[$book_item] += 10;
                            } else {
                                $suggest_books[$book_item] = 10;
                            }
                        }
                        return $suggest_books;

                    }
                });
                $i += 9;
            }

            foreach(array_filter($total_suggested_books) as $total_suggested_book){
                $count = count($total_suggested_book);
                foreach($total_suggested_book as $book_item => $ranking_value){
                    if(isset($best_books[$book_item])){
                        $best_books[$book_item] += $ranking_value + $count;
                    } else {
                        $best_books = $this->getRankingFromAmazonReview($best_books, $book_item);
                        $best_books[$book_item] = $ranking_value + $count;
                    }
                    $count--;
                }
            }

            if(!empty($best_books)){
                arsort($best_books);
                foreach($best_books as $key => $book_item){
                    $isbn = $key;
                    $article_id = $article->id;
                    $search_query = [
                        'Operation' => 'ItemLookup',
                        'ResponseGroup' => 'Medium',
                        'ItemId' => $isbn
                    ];

                    $amazon_response = Helper::amazonAdAPI($search_query);

                    if(isset($amazon_response['Items']['Item'])){
                        $get_amazon_items = $amazon_response['Items']['Item'];
                    } else {
                        $get_amazon_items = null;
                    }

                    if(!empty($get_amazon_items)){
                        $item = $get_amazon_items ;

                        $keyword_array = explode(" ", strtolower($input['keyword']));
                        $match_count = 0;
                        foreach($keyword_array as $keyword_item){
                            if (strpos(strtolower($item['ItemAttributes']['Title']), $keyword_item) !== false) {
                                $match_count++;
                            }
                        }

                        if ($match_count > 0) {
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

                            $author_name = null;
                            if(isset($item['ItemAttributes']['Author'])){
                                $author_name = $item['ItemAttributes']['Author'];
                                if(is_array($author_name)){
                                    $author_name = implode(',', $author_name);
                                }
                            }

                            if(isset($item['ItemAttributes']['PublicationDate'])){
                                $publication_date = $item['ItemAttributes']['PublicationDate'];
                            } else {
                                $publication_date = null;
                            }


                            if( strlen($publication_date) == 7 ){
                                $publication_date = $publication_date. '-01';
                            } elseif (strlen($publication_date) == 4) {
                                $publication_date = $publication_date. '-01'.'-01';
                            }

                            if (isset($item['LargeImage']['URL'])){
                                $product_image = $item['LargeImage']['URL'];
                            } elseif(isset($item['MediumImage']['URL'])) {
                                $product_image = $item['MediumImage']['URL'];
                            } elseif (isset($item['SmallImage']['URL'])){
                                $product_image = $item['SmallImage']['URL'];
                            } else {
                                $product_image = '';
                            }

                            $product = new Product();
                            $product->isbn = $item['ASIN'];
                            $product->product_title = $item['ItemAttributes']['Title'];
                            $product->product_description = $editorial_details;
                            $product->amazon_link = $item['DetailPageURL'];
                            $product->image_url = $product_image;
                            $product->author_name = $author_name;
                            $product->article_id = $article_id;
                            $product->publication_date = $publication_date;
                            $product->save();
                        }
                    }
                    sleep(2);
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

    }

    public function getRankingFromAmazonReview($best_books, $asin){
        \Log::info("--------------$asin--------\n");

        $client = new Client();
        $crawler = $client->request('GET', "https://www.amazon.com/product-reviews/$asin/ref=cm_cr_arp_d_viewopt_srt?sortBy=recent&pageNumber=1", ['verify' => false]);
        $raking_review_dates = $crawler->filter('.review-date')->each(function ($node) {
            return (int)substr($node->text(), -1)*2;
        });

        $raking_total_review = $crawler->filter('.totalReviewCount')->each(function ($node) {
            return (int)$node->text();
        });

        $raking_rating = $crawler->filter('.arp-rating-out-of-text')->each(function ($node) {
            return (int)((float)(substr($node->text(), 0, 3)) * 10);
        });

        $total_marks = array_merge($raking_review_dates, $raking_total_review, $raking_rating);

        foreach($total_marks as $mark){
            if(isset($best_books[$asin])){
                $best_books[(string)$asin] += $mark;
            } else {
                $best_books[(string)$asin] = 1;
            }
        }

        sleep(2);

        return $best_books;
    }
}
