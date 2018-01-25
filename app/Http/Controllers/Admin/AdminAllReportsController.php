<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Session;
use Auth;
use Carbon\Carbon;
use App\Models\ProductOrder;
use App\Models\SiteCost;

class AdminAllReportsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user()->roleType->name == 'admin'){
            $product_orders = ProductOrder::all();

            $total_whole_sell = 0;
            $total_sell_from_article = 0;
            $total_sell_from_non_article = 0;
            foreach($product_orders as $product_order){
                if(empty($product_order->article_id)){
                    $total_sell_from_non_article += $product_order->ad_fees;
                } else {
                    $total_sell_from_article += $product_order->ad_fees;
                }
            }

            $total_whole_sell = $total_sell_from_non_article + $total_sell_from_article;

            $site_costs = SiteCost::all();

            $all_costs = [];
            $individual_costs = [];
            $total_costs = 0;

            foreach($site_costs as $key => $site_cost){
                $cost_type_name = $site_cost->site_cost_type->name;
                $username = $site_cost->user->name;
                if(!isset($all_costs[(string)$cost_type_name])){
                    $all_costs[(string)$cost_type_name] = 0;
                }
                $all_costs[(string)$cost_type_name] += $site_cost->amount;

                if(!isset($individual_costs[(string)$username])){
                    $individual_costs[(string)$username] = 0;
                }

                $individual_costs[(string)$username] += $site_cost->amount;

                $total_costs += $site_cost->amount;
            }

            $articles = Article::where('status', true)->orderBy('created_at', 'desc')->get();
            $last_article = Article::where('status', true)->orderBy('created_at', 'desc')->first();

            $total_articles = [];
            $individual_articles = [];

            foreach($articles as $article){
                $date = Carbon::parse($article->created_at)->format('Y_F');
                if(!isset($total_articles[(string)$date])){
                    $total_articles[(string)$date] = 0;
                }
                $total_articles[(string)$date] += 1;


                $username = $article->user->name;

                if(!isset($individual_articles[(string)$username])){
                    $individual_articles[(string)$username] = 0;
                }
                $individual_articles[(string)$username] += 1;
            }

            return view('admin.all_reports.index', compact('total_sell_from_article',
                'site_costs', 'total_sell_from_non_article', 'total_whole_sell',
                'all_costs', 'total_costs', 'articles', 'last_article', 'total_articles', 'individual_costs',
                'individual_articles'));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
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
    public function update()
    {

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

}
