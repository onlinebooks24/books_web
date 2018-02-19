<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// we will use Mail namespace
use Mail;
use Session;

class MailController extends Controller
{
    // first, we create function for send Basics email
    public function basic_email(Request $request){
        Mail::send(['html'=>'mail_template.collect_mail_queue'], [], function ($message)
        {
            $message->subject('this is subject');
            $message->from('info@esljobslounge.com', 'ESL Jobs Lounge');

            $message->to('mashpysays@gmail.com');

        });

        dd('tet');
        return redirect()->route('blog.index');
    }
   
}