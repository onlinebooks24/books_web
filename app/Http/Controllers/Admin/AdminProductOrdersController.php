<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Product;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use Session;
use Carbon\Carbon;

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
            $product_orders = ProductOrder::where('article_id', null)->orderBy('id','desc')->Paginate(50);
        } else {
            $product_orders = ProductOrder::orderBy('id','desc')->Paginate(50);
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

        if($request['xml_type'] == 'earning'){

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

                    $check_product_order_article = ProductOrder::where('product_number', $product_number)->first();

                    if(!empty($check_product_order_article)){
                        $product_id = $check_product_order_article->product_id;
                        $article_id = $check_product_order_article->article_id;
                        $manually_inserted_on_article = false;
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
        } else if($request['xml_type'] == 'bounty'){
            foreach($xmlArray['Items']['Item'] as $item){
                if(!isset($item['DateShipped'])){
                    $item = $item['@attributes'];
                }
                $product_number = 'bounty_'. Carbon::parse($item['DateShipped'])->timestamp;
                $title = $item['title'];
                $shipment_date = $item['DateShipped'];
                $ad_fees = $item['AdFees'];
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
        $product_order = ProductOrder::find($id);

        $product_title_array = explode(" ",$product_order->title);

        $exclude_lists = ['the', 'a', 'with', 'to', 'for', 'how', 'in', 'book', ':', 'and', '&', 'Beginners'];

        $article_array = [];

        foreach($product_title_array as $product_title_item){
            if(!in_array(strtolower($product_title_item), $exclude_lists)){
                $article = Article::where('title', 'LIKE', "% $product_title_item %")->pluck('id')->toArray();
//                $article_array[$product_title_item] = $article;
                $article_array = array_unique(array_merge($article_array, $article));
            }
        }

//        dd($article_array);

        $suggestion_articles = Article::whereIn('id', $article_array)->get();
        return view('admin.product_orders.edit', compact('product_order', 'suggestion_articles'));
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
        $product_order = ProductOrder::find($id);
        $product_order->product_number = $request['product_number'];
        $product_order->title = $request['title'];
        $product_order->product_id = $request['product_id'];
        $product_order->shipment_date = $request['shipment_date'];
        $product_order->ad_fees = $request['ad_fees'];
        $product_order->manually_inserted_on_article = $request['manually_inserted_on_article'];
        $product_order->article_id = $request['article_id'];
        $product_order->product_type = $request['product_type'];
        $product_order->save();

        $flash_message = 'Successfully updated';
        Session::flash('message', $flash_message);

        return redirect()->to(route('admin_product_orders.index') . '?unlinked=yes');
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
