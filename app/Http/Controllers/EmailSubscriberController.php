<?php

namespace App\Http\Controllers;

use App\Models\EmailSubscriberCategory;
use Illuminate\Http\Request;
use App\Models\EmailSubscriber;
use Auth;

class EmailSubscriberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function SubscribeNow(Request $request){
        $email = $request['email'];

        setcookie ("email", "", time() - 3600);
        setcookie("email", $email, 2147483647);

        $check_email_exist = EmailSubscriber::where('email', $email)->first();

        if(empty($check_email_exist)){
            $email_subscriber = new EmailSubscriber();

            $email_subscriber->full_name = null;
            $email_subscriber->email = $email;
            $email_subscriber->temporary = false;
            $email_subscriber->subscribe = true;
            $email_subscriber->	source = 's';  // from our site

            $email_subscriber->save();

            $email_subscriber_message = 'success';

        } else {
            $check_email_exist->subscribe = true;
            $check_email_exist->save();
            $email_subscriber_message = 'exist';
        }

        return view('frontend.email_subscribers.email_subscribe_confirmation', compact('email_subscriber_message'));
    }

    public function UpdateCategorySubscriber(Request $request){
        $email = $request['email'];
        $category_id = $request['category_id'];

        $email_subscriber = EmailSubscriber::where(['email' => $email, 'subscribe' => true])->first();

        $result = false;

        if(!empty($email_subscriber)){
            $check_already_exist = EmailSubscriberCategory::where('email_subscriber_id', $email_subscriber->id )
                                                    ->where('category_id', $category_id)->first();

            if(empty($check_already_exist)){
                $email_subscriber_category = new EmailSubscriberCategory();
                $email_subscriber_category->email_subscriber_id = $email_subscriber->id;
                $email_subscriber_category->category_id = $category_id;
                $email_subscriber_category->save();
                $result = true;
            } else {
                $result = false;
            }

        } else {
            $result = false;
        }

        return response()->json(['result' => $result]);

    }

    public function Unsubscribe(Request $request){
        $email = $request['email'];

        $email_subscriber = EmailSubscriber::where('email', $email)->first();

        if(!empty($email_subscriber)){
            $email_subscriber->subscribe = false;
            $email_subscriber->save();

            EmailSubscriberCategory::where('email_subscriber_id', $email_subscriber->id )->delete();
        }

        return view('frontend.email_subscribers.email_unsubscribe');
    }
}
