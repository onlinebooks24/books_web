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
use App\User;

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

            $total_sell_from_article = 0;
            $total_sell_from_non_article = 0;
            foreach($product_orders as $product_order){
                $product_order_date = Carbon::parse($product_order->shipment_date)->format('Y_F');
                if(!isset($monthly_product_sell[(string)$product_order_date])){
                    $monthly_product_sell[(string)$product_order_date] = 0;
                }
                $monthly_product_sell[(string)$product_order_date] += $product_order->ad_fees;

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

                $site_cost_date = Carbon::parse($site_cost->when_paid)->format('Y_F');
                if(!isset($monthly_site_cost[(string)$site_cost_date])){
                    $monthly_site_cost[(string)$site_cost_date] = 0;
                }
                $monthly_site_cost[(string)$site_cost_date] += $site_cost->amount;
            }

            $articles = Article::where('status', true)->orderBy('created_at', 'desc')->get();
            $last_article = Article::where('status', true)->orderBy('created_at', 'desc')->first();
            $last_article = ($last_article->created_at->diff(Carbon::now())->days) . ' days ago';

            $total_articles = [];
            $individual_articles = [];
            $individual_cost = [];
            $individual_revenue = [];
            $individual_no_sell = [];
            $article_investment_return = [];
            $article_view_count = 0;
            $monthly_article_view_count = [];

            foreach($articles as $article){
                $article_view_count +=  $article->count;
                $date = Carbon::parse($article->created_at)->format('Y_F');
                if(!isset($total_articles[(string)$date])){
                    $total_articles[(string)$date] = 0;
                }
                $total_articles[(string)$date] += 1;

                if(!isset($monthly_article_view_count[(string)$date])){
                    $monthly_article_view_count[(string)$date] = 0;
                }
                $monthly_article_view_count[(string)$date] += $article->count;

                $username = $article->user->name;

                if(!isset($individual_articles[(string)$username])){
                    $individual_articles[(string)$username] = 0;
                }
                $individual_articles[(string)$username] += 1;

                if(!isset($individual_cost[(string)$username])){
                    $individual_cost[(string)$username] = 0;
                }

                $cost = 0;
                if(!empty($article->site_costs)){
                    foreach($article->site_costs as $item){
                        $cost += $item->amount;
                    }
                }

                $individual_cost[(string)$username] += $cost;

                if(!isset($individual_revenue[(string)$username])){
                    $individual_revenue[(string)$username] = 0;
                }

                $fee = 0;
                if(!empty($article->product_orders)){
                    foreach($article->product_orders as $item){
                        $fee += $item->ad_fees;
                    }
                }

                $individual_revenue[(string)$username] += $fee;

                if(!isset($article_investment_return[(string)$date])){
                    $article_investment_return[(string)$date] = 0;
                }

                $article_investment_return[(string)$date] += $fee;

                if(!isset($individual_no_sell[(string)$username])){
                    $individual_no_sell[(string)$username] = 0;
                }

                if($fee == 0){
                    $individual_no_sell[(string)$username] += 1;
                }

            }

            $active_writers = User::where(['active_writer' => true])
                ->get();


            return view('admin.all_reports.index',
                compact('total_sell_from_article',
                        'site_costs', 'total_sell_from_non_article',
                        'monthly_product_sell', 'monthly_site_cost',
                        'total_whole_sell', 'all_costs', 'total_costs',
                        'articles', 'last_article', 'total_articles',
                        'individual_costs', 'individual_articles',
                        'individual_cost', 'individual_revenue',
                        'individual_no_sell', 'article_investment_return',
                        'article_view_count', 'monthly_article_view_count', 'active_writers'));
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
