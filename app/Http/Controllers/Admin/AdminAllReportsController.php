<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
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
        $total_costs = 0;
        $tmp_cost = 0;

        foreach($site_costs as $site_cost){
            $cost_type_name = $site_cost->site_cost_type->name;
            $all_costs[(string)$cost_type_name] = $tmp_cost + $site_cost->amount;
            $tmp_cost = $site_cost->amount;
            $total_costs += $site_cost->amount;
        }

        return view('admin.all_reports.index', compact('total_sell_from_article',
            'site_costs', 'total_sell_from_non_article', 'total_whole_sell', 'all_costs', 'total_costs'));
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
