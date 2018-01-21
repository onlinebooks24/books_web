<?php

namespace App\Http\Controllers;

use Plivo\RestClient;
use Plivo\XML\Response;
use Auth;

class VoiceCallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
//        $voice_message = "I am from online books review. Please complete javascript article as soon as possible.";
//        $voice_message =  $voice_message. 'I again repeat '. $voice_message;
//
//        $voice_message_url = route('voice_call.call_template', $voice_message);
//
//        $client = new RestClient("MAMDY4ZJNJYTQ0MZJKMZ", "ZjFiNDNjMDNkOTEzNmJjMmVjYjJiZTc2OTViMmFi");
//        $call_made = $client->calls->create(
//            '+14154847489',
//            ['+8801823387518'],
//            $voice_message_url,
//            'GET'
//        );
//
//        dd($call_made);


    }

    public function call_template($voice_message){
        $content = view('voice_call.call_template', compact('voice_message'));

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
