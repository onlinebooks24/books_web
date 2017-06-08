<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

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

}