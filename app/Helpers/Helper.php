<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use App\Models\Product;
use Carbon\Carbon;
use PulkitJalan\GeoIP\GeoIP;
use App\Models\Category;

class Helper
{
    public static function readMoreHelper($string) {
        $new_short_string = substr($string, 0, strpos($string, '<span class="hidden">read more</span>'));

        if(!empty($new_short_string)){
            $string = $new_short_string;
        }

        return $string;
    }

    public static function amazonAdAPI($payload, $action){
        try {
            $host = "webservices.amazon.com";
            $uriPath = "/paapi5/" . strtolower($action);
            $partner_tag = env('AssociateTag');

            $awsv4 = new AwsV4(env('AccessKey'), env('SecretKey'));
            $awsv4->setRegionName("us-east-1");
            $awsv4->setServiceName("ProductAdvertisingAPI");
            $awsv4->setPath ($uriPath);
            $awsv4->setHost($host);
            $awsv4->setAction($action);
            $awsv4->setPartnerTag($partner_tag);
            $awsv4->setPayload($payload);

            $awsv4->setRequestMethod ("POST");
            $awsv4->addHeader ('content-encoding', 'amz-1.0');
            $awsv4->addHeader ('content-type', 'application/json; charset=utf-8');
            $awsv4->addHeader ('host', $host);
            $awsv4->addHeader ('x-amz-target', 'com.amazon.paapi5.v1.ProductAdvertisingAPIv1.' . $action);
            return $product = $awsv4->getResponse();

        } catch(Exception $e) {
            echo "something went wrong: <br>";
            echo $e->getMessage();
        }
    }

    public static function get_location(){
        $geoip = new GeoIP();

        $location = null;
        if(!empty($geoip->getLatitude())){
            $location['latitude'] = $geoip->getLatitude();
            $location['longitude'] = $geoip->getLongitude();
            $location['country_code'] = $geoip->getCountryCode();
            $location['city'] = $geoip->getCity();
            $location['region_name'] = $geoip->getRegion();
        } else {
            $json = file_get_contents("http://api.ipstack.com/check?access_key=c3cf68970ba8a4692405dde220de4a0b&format=1");
            $obj = json_decode($json);

            if(isset($obj->latitude)){
                $location['latitude'] = $obj->latitude;
                $location['longitude'] = $obj->longitude;
                $location['country_code'] = $obj->country_code;
                $location['city'] = $obj->city;
                $location['region_name'] = $obj->region_name;
            }
        }
        return $location;
    }

    public static function get_child_category($browse_node_id){
        $check_category_ids = [$browse_node_id];
        $category_ids = [];


        while(!empty($check_category_ids)){
            $check_category_ids = Category::whereIn('parent_id', $check_category_ids)->pluck('browse_node_id')->toArray();
            $category_ids = array_unique(array_merge($check_category_ids , $category_ids));
        }

        $category_ids = array_merge($category_ids , [$browse_node_id]);
        $category_ids = Category::whereIn('browse_node_id', $category_ids)->pluck('id')->toArray();

        return $category_ids;
    }

    public static function addProduct($isbn, $article_id, $keywords = null){
        $payload = [
            'ItemIds' => [$isbn]
        ];

        $amazon_response = Helper::amazonAdAPI($payload, 'GetItems');
        $amazon_response = $amazon_response->response;
        $amazon_response = json_decode($amazon_response);

        $item = $amazon_response->ItemsResult->Items[0];
        if($item){
            $get_amazon_items = $item;
        } else {
            $get_amazon_items = null;
        }

        if(!empty($get_amazon_items)){
            $item = $get_amazon_items ;
            $editorial_details = self::getDescription($isbn);
            $date = '';
            $author_name = $get_amazon_items->ItemInfo->ByLineInfo->Contributors[0]->Name;
            $publication_date = isset($get_amazon_items->ItemInfo->ContentInfo->PublicationDate)? $get_amazon_items->ItemInfo->ContentInfo->PublicationDate->DisplayValue : '';
            $release_date = isset($get_amazon_items->ItemInfo->ProductInfo->ReleaseDate)? $get_amazon_items->ItemInfo->ProductInfo->ReleaseDate->DisplayValue : '';
            if (!empty($publication_date)) {
                $date = $publication_date;
            }else {
                if (!empty($release_date)) {
                    $date = $release_date;
                }
            }
            $match_count = 1;

            if ($keywords) {
                $match_count = 0;
                $keyword_array = explode(" ", strtolower($keywords));
                foreach($keyword_array as $keyword_item){
                    if (strpos(strtolower($item->ItemInfo->Title->DisplayValue), $keyword_item) !== false) {
                        $match_count++;
                    }
                }
            }


            if ($match_count > 0) {
                $product = new Product();
                $product->isbn = $item->ASIN;
                $product->product_title = $item->ItemInfo->Title->DisplayValue;
                $product->product_description = $editorial_details;
                $product->amazon_link = $item->DetailPageURL;
                $product->image_url = $item->Images->Primary->Large->URL;
                $product->author_name = $author_name;
                $product->publication_date = $date? Carbon::parse($date)->format('Y-m-d 00:00:00') : '';
                $product->article_id = $article_id;
                $product->save();
            }

        }
    }
    
    public static function getDescription($isbn) {
      $url = 'https://www.amazon.com/dp/'.$isbn;
      list($status) = get_headers($url);
      if (strpos($status, '404') !== TRUE) {
        $response = file_get_contents('https://www.amazon.com/dp/'.$isbn);
        $minify_response = self::Minify_Html(json_decode(json_encode($response)));
    
        $pattern = "/<noscript>(.*?)<\/noscript>/";
        preg_match_all($pattern, $minify_response, $matches);
    
        $matches = collect($matches[1])->filter(function($match) {
          return strlen(strip_tags($match)) > 0;
        });
        return strip_tags($matches[1]);
      } else {
        return $description = '';
      }
    }
  
    public static function Minify_Html($Html)
    {
      $Search = array(
        '/(\n|^)(\x20+|\t)/',
        '/(\n|^)\/\/(.*?)(\n|$)/',
        '/\n/',
        '/\<\!--.*?-->/',
        '/(\x20+|\t)/', # Delete multispace (Without \n)
        '/\>\s+\</', # strip whitespaces between tags
        '/(\"|\')\s+\>/', # strip whitespaces between quotation ("') and end tags
        '/=\s+(\"|\')/'); # strip whitespaces between = "'
      
      $Replace = array(
        "\n",
        "\n",
        " ",
        "",
        " ",
        "><",
        "$1>",
        "=$1");
      
      $Html = preg_replace($Search,$Replace,$Html);
      return $Html;
    }

}
