<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Session;
use App\Models\EmailSubscriber;
use App\Models\EmailSubscriberCategory;

class AdminTemporaryEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $access_token = env('githubToken');
        $client = new \GuzzleHttp\Client();
        $email = [];
        $category_id = 5;

        $search_url = $client->get('https://api.github.com/search/code?per_page=20&order=desc&q=tensorflow&sort=indexed&access_token='. $access_token);
        $event_json =  json_decode($search_url->getBody());

        foreach($event_json->items as $search_item) {
            $username = $search_item->repository->owner->login;
            $event_url = $client->get('https://api.github.com/users/'. $username .'/events/public?access_token='. $access_token);
            $event_json =  json_decode($event_url->getBody());

            foreach($event_json as $item){
                if(isset($item->payload->commits[0])){
                    $collect_email = $item->payload->commits[0]->author->email;

                    if (!in_array($collect_email, $email) &&
                        filter_var($collect_email, FILTER_VALIDATE_EMAIL) &&
                        strpos($collect_email, 'noreply') == false &&
                        strpos($collect_email, 'local') == false &&
                        strpos($collect_email, 'internal') == false ){
                        $email[] = $collect_email;

                        $email_subscriber = EmailSubscriber::where('email', $item)->first();

                        if(empty($email_subscriber)){
                            $email_subscriber = new EmailSubscriber();
                            $email_subscriber->full_name = null;
                            $email_subscriber->email = $item;
                            $email_subscriber->temporary = true;
                            $email_subscriber->subscribe = true;
                            $email_subscriber->	source = 'g';  // from our site

                            $email_subscriber->save();

                            $check_already_exist = EmailSubscriberCategory::where('email_subscriber_id', $email_subscriber->id )
                                ->where('category_id', $category_id)->first();

                            if(empty($check_already_exist)){
                                $email_subscriber_category = new EmailSubscriberCategory();
                                $email_subscriber_category->email_subscriber_id = $email_subscriber->id;
                                $email_subscriber_category->category_id = $category_id;
                                $email_subscriber_category->save();
                            }
                        }
                    }
                }
            }
            sleep(1);
        };

        dd($email);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

    }

}
