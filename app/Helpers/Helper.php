<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use PulkitJalan\GeoIP\GeoIP;
use App\Models\Category;

require 'Paapi.php';

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
        try {
            $host = "webservices.amazon.com";
            $uriPath = "/paapi5/getitems";
            $partner_tag = env('AssociateTag');
            $amazon_id = $search_query;

            $awsv4 = new \AwsV4(env('AccessKey'), env('SecretKey'));
            $awsv4->setRegionName("us-east-1");
            $awsv4->setServiceName("ProductAdvertisingAPI");
            $awsv4->setPath ($uriPath);
            $awsv4->setHost($host);
            $awsv4->setItemID($amazon_id);
            $awsv4->setPartnerTag($partner_tag);
            $awsv4->setPayload();

            $awsv4->setRequestMethod ("POST");
            $awsv4->addHeader ('content-encoding', 'amz-1.0');
            $awsv4->addHeader ('content-type', 'application/json; charset=utf-8');
            $awsv4->addHeader ('host', $host);
            $awsv4->addHeader ('x-amz-target', 'com.amazon.paapi5.v1.ProductAdvertisingAPIv1.GetItems');
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

}