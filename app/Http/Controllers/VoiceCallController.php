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
        $voice_message_url = route('voice_call.call_template');

//        dd($voice_message_url);

        $client = new RestClient("MAMDY4ZJNJYTQ0MZJKMZ", "ZjFiNDNjMDNkOTEzNmJjMmVjYjJiZTc2OTViMmFi");
        $call_made = $client->calls->create(
            '+14154847489',
            ['+8801670633325'],
            $voice_message_url,
            'GET'
        );

        dd($call_made);


    }

    public function call_template(){
        $content = view('voice_call.call_template');

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
