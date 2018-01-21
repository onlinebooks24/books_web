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
//        $accountId = env('twilioKeyAccountId');
//        $token = env('twilioKeySecret');
//        $fromNumber = '+16138006902';
//        $twilio = new \Aloha\Twilio\Twilio($accountId, $token, $fromNumber);
//        $test = $twilio->call('+8801996476778', function ($message) {
//            $message->say('Hi Faria. Hope you are fine. I am from online books review. Please publish new article as soon as possible. Ok. For today. take care. Bye bye.');
//        });
//        dd($test);


        $last_article = Article::where('status', true)->orderBy('created_at', 'desc')->first();
        $last_article_published_at = Carbon::parse($last_article->created_at)->format('l jS \\of F Y ');
        $created = new Carbon($last_article->created_at);
        $now = Carbon::now();
        $difference = ($created->diff($now)->days);

        $send_call = null;

        if($difference >= 1){
            $accountId = env('twilioKeyAccountId');
            $token = env('twilioKeySecret');
            $fromNumber = '+16138006902';

            $admin_users = User::whereIn('id', [1,2])->get();

            foreach($admin_users as $user){
                $voice_message = ",.. Hi, $user->name. Hope you are fine. I am from online books review. "
                    . " Last time you have published article on ". $last_article_published_at
                    . ". Please publish new article, as soon as possible. Bye Bye. Take care.";

                $phone_no = $user->phone;
                $twilio = new \Aloha\Twilio\Twilio($accountId, $token, $fromNumber);
                $send_call = $twilio->call($phone_no, function ($message) use ($voice_message) {
                    $message->say($voice_message, ['voice' => 'woman', 'language' => 'en']);
                });
            }
        }

        dd($send_call);

    }

    public function call_template($voice_message){
        $content = view('voice_call.call_template', compact('voice_message'));

        return response($content, 200)
            ->header('Content-Type', 'text/xml');
    }
}
