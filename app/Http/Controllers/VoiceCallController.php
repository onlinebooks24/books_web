<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Carbon\Carbon;
use Auth;
use Illuminate\Foundation\Auth\User;

class VoiceCallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $accountId = env('twilioKeyAccountId');
        $token = env('twilioKeySecret');
        $fromNumber = '+8801670633325';
        $twilio = new \Aloha\Twilio\Twilio($accountId, $token, $fromNumber);
        $test = $twilio->call('+8801670633325', function ($message) {
            $message->say('Hi Faria. Hope you are fine. I am from online books review. Please publish new article as soon as possible. Ok. For today. take care. Bye bye.');
        });
        dd($test->sid);
//        $sdk = $twilio->getTwilio();
//dd($sdk->account->calls);
//        $twilio = new LoggingDecorator($psrLogger, new \Aloha\Twilio\Dummy());

    }

    public function call_template($voice_message){
        $content = view('voice_call.call_template', compact('voice_message'));

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
