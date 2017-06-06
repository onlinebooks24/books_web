<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class AdminAutoArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search_query = [
            'Operation' => 'ItemSearch',
            'ResponseGroup' => 'Medium',
            'Keywords' => 'Laravel',
            'SearchIndex' => 'Books',
        ];

        $amazon_response = $this->amazonAdAPI($search_query);
        $get_amazon_items = $amazon_response['Items']['Item'];

        foreach($get_amazon_items as $item){
            $product = new Product();
            $product->isbn = $item['ASIN'];
            $product->product_title = $item['ItemAttributes']['Title'];
            $product->product_description = $item['EditorialReviews']['EditorialReview']['Content'];
            $product->brand_id = 'amazon';
            $product->link = $item['DetailPageURL'];
            $product->image_url = $item['LargeImage']['URL'];
            $product->author_id = $item['ItemAttributes']['Author'];
            $product->article_id = 1;

            $product->save();
            dd($product);
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

    public function amazonAdAPI($search_query){
        $client = new \GuzzleHttp\Client();

        $access_key = env('AccessKey');
        $secret_key = env('SecretKey');
        $associate_tag = env('AssociateTag');

        $timestamp = date('c');

        $query = [
            'Service' => 'AWSECommerceService',
            'AssociateTag' => $associate_tag,
            'AWSAccessKeyId' => $access_key,
            'Timestamp' => $timestamp
        ];

        $query = array_merge($query , $search_query);

        ksort($query);

        $sign = http_build_query($query);

        $request_method = 'GET';
        $base_url = 'webservices.amazon.com';
        $endpoint = '/onca/xml';

        $string_to_sign = "{$request_method}\n{$base_url}\n{$endpoint}\n{$sign}";
        $signature = base64_encode(
            hash_hmac("sha256", $string_to_sign, $secret_key, true)
        );

        $query['Signature'] = $signature;

        try {
            $response = $client->request(
                'GET', 'http://webservices.amazon.com/onca/xml',
                ['query' => $query]
            );

            $contents = ($response->getBody()->getContents());
            $xml = simplexml_load_string($contents, "SimpleXMLElement", LIBXML_NOCDATA);
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);
            return $array;

        } catch(Exception $e) {
            echo "something went wrong: <br>";
            echo $e->getMessage();
        }
    }
}
