<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
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

    public static function amazonAdAPI($search_query){
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

        $category_ids = Category::whereIn('browse_node_id', $category_ids)->pluck('id')->toArray();

        return $category_ids;
    }

}