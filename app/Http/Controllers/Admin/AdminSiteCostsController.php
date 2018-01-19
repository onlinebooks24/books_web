<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductOrder;
use App\Models\SiteCost;
use App\Models\SiteCostType;
use Illuminate\Http\Request;
use Session;

class AdminSiteCostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $site_costs = SiteCost::orderBy('created_at', 'desc')->Paginate(50);
        return view('admin.site_costs.index', compact('site_costs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $site_cost_types = SiteCostType::all();
        return view('admin.site_costs.create', compact('site_cost_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $description = $request['description'];
        $site_cost_type_id = $request['site_cost_type_id'];
        $amount = $request['amount'];
        $when_paid = $request['when_paid'];
        $article_id = $request['article_id'];

        $site_cost = new SiteCost();
        $site_cost->description = $description;
        $site_cost->site_cost_type_id = $site_cost_type_id;
        $site_cost->amount = $amount;
        $site_cost->when_paid = $when_paid;
        $site_cost->article_id = $article_id;
        $site_cost->save();

        $flash_message = 'Successfully Saved';
        Session::flash('message', $flash_message);

        return redirect()->to(route('admin_site_costs.index'));
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
