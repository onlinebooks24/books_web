<?php

namespace App\Http\Controllers;

use Auth;

class VoiceCallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $accountId = 'AC77bc03c12cfc693c2370916305199de9';
        $token = '1c69bd849e86cd12e3aec1d042241091';
        $fromNumber = '+16138006902';
        $twilio = new \Aloha\Twilio\Twilio($accountId, $token, $fromNumber);
        $test = $twilio->call('+8801670633325', function ($message) {
            $message->say('Listen this song:');
            $message->play('http://banglasongs.fusionbd.com/downloads/download.php?file=mp3/bangla/Pritom-Pritom_Hasan/04.Mayer_Kole-Pritom_ft._Momtaz_FusionBD.Com.mp3', ['loop' => 1]);
        });

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
