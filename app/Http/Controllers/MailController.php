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
    	$subject = $request['subject'];
    	$content = $request['message'];
    	$user_name = $request['name'];
    	$user_email = $request['email'];

        $data=['name'=> $user_name , 'subject' => $subject , 'email' => $user_email ,'content' => $content ];
        Mail::send(['text'=>'mail'], $data, function($message){
            $message->to('somratcste@gmail.com','G. M. Nazmul Hossain')->subject('From ServerEditor');
            $message->from('servereditor@gmail.com','ServerEditor');
        });
        Session::flash('message', 'Thank you. Your mail was sent .');
        return redirect()->route('blog.index');
    }
   
}