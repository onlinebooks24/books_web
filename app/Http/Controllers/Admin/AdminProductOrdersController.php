<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use Session;

class AdminProductOrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request['unlinked'] == 'yes'){
            $product_orders = ProductOrder::where('article_id', null)->orderBy('created_at','desc')->Paginate(50);
        } else {
            $product_orders = ProductOrder::orderBy('created_at','desc')->Paginate(50);
        }
        return view('admin.product_orders.index', compact('product_orders'));
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
        $xmlResponse = file_get_contents($request['product_orders_file']);
        $xmlResponse = simplexml_load_string($xmlResponse);
        $xmlArray = json_decode(json_encode($xmlResponse), true);
        $successfully_import_count = 0;
        $failed_import_count = 0;

        foreach($xmlArray['Items']['Item'] as $item){
            $product_number = $item['@attributes']['ASIN'];
            $title = $item['@attributes']['title'];
            $shipment_date = $item['@attributes']['DateShipped'];
            $ad_fees = $item['@attributes']['AdFees'];
            $manually_inserted_on_article = false;
            $product_id = null;
            $article_id = null;

            $check_product_order_exist = ProductOrder::where('product_number', $product_number)
                                                        ->where('shipment_date', $shipment_date)->first();
            if(empty($check_product_order_exist)){
                $check_product_exist = Product::where('isbn', $product_number)->first();

                if(!empty($check_product_exist)){
                    $product_id = $check_product_exist->id;
                    $article_id = $check_product_exist->article->id;
                }

                $product_order = new ProductOrder();
                $product_order->product_number = $product_number;
                $product_order->title = $title;
                $product_order->product_id = $product_id;
                $product_order->shipment_date = $shipment_date;
                $product_order->ad_fees = $ad_fees;
                $product_order->manually_inserted_on_article = $manually_inserted_on_article;
                $product_order->article_id = $article_id;
                $product_order->product_type = 1; //Planning to add product type like amazon, ebay
                $product_order->save();
                $successfully_import_count++ ;
            } else {
                $failed_import_count++;
            }
        }

        $flash_message = "Successfully imported =>". $successfully_import_count . " and failed imported =>". $failed_import_count;

        Session::flash('message', $flash_message);

        return redirect()->to(route('admin_product_orders.index'));
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
