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
            $article->waiting_for_approval = false;
            $article->meta_description = "Get best $title books";
            $article->slug = $slug;
            $article->save();

            for($i = 1; $i <= 3; $i++){
                $search_query = [
                    'ResponseGroup' => 'Medium',
                    'Keywords' => $keyword,
                    'ItemPage' => $i
                ];

                $amazon_response = Helper::amazonAdAPI($search_query, 'SearchItems')->response;
                $amazon_response = json_decode($amazon_response);
                if(isset($amazon_response->SearchResult->Items)){
                    $get_amazon_items = $amazon_response->SearchResult->Items;
                } else {
                    $get_amazon_items = null;
                }

                if(!empty($get_amazon_items)){

                    foreach($get_amazon_items as $item){
                        if(isset($item->ASIN)){
                            $asin = $item->ASIN;
                            $best_books = $this->getRankingFromAmazonReview($best_books, $asin);
                        }
                    }
                }
                sleep(2);
            }

            $client = new Client();
            $client->setHeader('user-agent', "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36");
            $total_suggested_books = null;
            $google_keyword = 'best+' . str_replace(' ', '+',  strtolower($title));

            \Log::info("--------------$google_keyword--------\n");

            for($i=0; $i <= 1; $i++){
                $search_google = "https://www.google.com/search?q=$google_keyword&ie=utf-8&oe=utf-8&client=firefox-b&start=$i";
                \Log::info("-----Searching Goolge---------$search_google--------\n");

                $crawler = $client->request('GET', $search_google , ['verify' => false]);
                $total_suggested_books =   $crawler->filter('.r a')->each(function ($node) {
                    $link_string = $node->attr('href');
                    $link_string = str_replace('/url?q=', '', $link_string);
                    $link_string = substr($link_string, 0, strpos($link_string, "&sa="));

                    if( filter_var($link_string, FILTER_VALIDATE_URL) && strpos($link_string, '.amazon.') == false){
                        $next_client = new Client();
                        $next_client->setHeader('user-agent', "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36");

                        \Log::info("------After Searching Entering URL--------$link_string--------\n");

                        try {
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
                        } catch (\Throwable $e) {
                            \Log::info("---Alert!!! Wrong in---$link_string--------\n");
                        }
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
                    $isbn = (string) $key;
                    $article_id = $article->id;
                    Helper::addProduct($isbn, $article_id, $input['keyword']);
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
        $client->setHeader('user-agent', "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.101 Safari/537.36");
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
