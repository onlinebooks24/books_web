<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Carbon\Carbon;
use Auth;

class VoiceCallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
//        $accountId = 'AC77bc03c12cfc693c2370916305199de9';
//        $token = '1c69bd849e86cd12e3aec1d042241091';
//        $fromNumber = '+16138006902';
//        $twilio = new \Aloha\Twilio\Twilio($accountId, $token, $fromNumber);
//        $test = $twilio->call('+8801996476778', function ($message) {
//            $message->say('Hi Faria. Hope you are fine. I am from online books review. Please publish new article as soon as possible. Ok. For today. take care. Bye bye.');
//        });
//        dd($test);


        $last_article = Article::where('status', true)->orderBy('created_at', 'desc')->first();

        $created = new Carbon($last_article->created_at);
        $now = Carbon::now();
        $difference = ($created->diff($now)->days);
        $voice_message = "Hi Mashpy. Hope you are fine. I am from online books review. Please publish new article as soon as possible ";
        $voice_message =  $voice_message. 'I again repeat '. $voice_message;

        if($difference >= 1){
            $accountId = 'AC77bc03c12cfc693c2370916305199de9';
            $token = '1c69bd849e86cd12e3aec1d042241091';
            $fromNumber = '+16138006902';
            $phone_no = '+880670633325';
            $twilio = new \Aloha\Twilio\Twilio($accountId, $token, $fromNumber);
            $url = 'https://www.onlinebooksreview.com//voice_call/call_template/' . $voice_message;
            dd($url);
            $twilio->call($phone_no, $url);
        }


    }

    public function call_template($voice_message){
        $content = view('voice_call.call_template', compact('voice_message'));

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
