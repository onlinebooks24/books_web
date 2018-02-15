<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CollectMailQueue;
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
        $collect_mail_queues = CollectMailQueue::where('run_cron_job', true)->orderByRaw("RAND()")->get();

        $total_request_attempt = CollectMailQueue::where('run_cron_job', true)->sum('limit_cron_job_attempt');

        $one_day = 86400; //second
        $total_email_limit = 30;
//        $average_sleep_time = $one_day/$total_request_attempt;
        $average_sleep_time = 1;
        $per_page = 100;
        $total_attempt = 0;
        $count = 0;

        foreach($collect_mail_queues as $queue_item){
            $topic = $queue_item->topic;
            $category_id = $queue_item->category_id;
            $collect_mail_queue_id = $queue_item->id;
            $current_attempt_limit = $queue_item->limit_cron_job_attempt;
            $count_attempt = $queue_item->limit_cron_job_attempt / $per_page;

                for($i = 0; $i < $count_attempt; $i++){
                    if($total_attempt <= $total_email_limit){
                        if($current_attempt_limit >= $total_email_limit){
                            $current_attempt_limit = $total_email_limit;
                        }
                        $current_attempt_count = $this->ping_github($topic, $category_id, $per_page, $average_sleep_time, $collect_mail_queue_id, $current_attempt_limit);
                        $total_attempt += $current_attempt_count;
                        $count++;
                    }
                }
            }

        dd($total_attempt. 'abc'. $count);
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

    public function ping_github($topic, $category_id, $per_page, $average_sleep_time, $collect_mail_queue_id, $current_attempt_limit){
        $access_token = env('githubToken');
        $client = new \GuzzleHttp\Client();
        $email = [];
        $username_array = [];
        $topic = str_replace(' ', '+', $topic);
        $current_attempt = 0;

        $search_url = $client->get('https://api.github.com/search/code?per_page='. $per_page .'&order=desc&q='. $topic .'&sort=indexed&access_token='. $access_token);
        $event_json =  json_decode($search_url->getBody());

        foreach($event_json->items as $search_item) {
            $username = $search_item->repository->owner->login;

            if(!in_array($username, $username_array) && ($current_attempt <= $current_attempt_limit)){
                $username_array[] = $username;
                $event_url = $client->get('https://api.github.com/users/'. $username .'/events/public?access_token='. $access_token);
                $event_json =  json_decode($event_url->getBody());
                foreach($event_json as $item){
                    if(isset($item->payload->commits[0])){
                        $collect_email = $item->payload->commits[0]->author->email;

                        if (!in_array($collect_email, $email) &&
                            filter_var($collect_email, FILTER_VALIDATE_EMAIL) &&
                            strpos($collect_email, 'noreply') == false &&
                            strpos($collect_email, 'local') == false &&
                            strpos($collect_email, 'internal') == false &&
                            strpos($collect_email, 'google') == false){
                            $email[] = $collect_email;

                            $email_subscriber = EmailSubscriber::where('email', $collect_email)->first();

                            if(empty($email_subscriber)){
                                $email_subscriber = new EmailSubscriber();
                                $email_subscriber->full_name = null;
                                $email_subscriber->email = $collect_email;
                                $email_subscriber->temporary = true;
                                $email_subscriber->subscribe = true;
                                $email_subscriber->source = 'g';  // from our site
                                $email_subscriber->collect_mail_queue_id = $collect_mail_queue_id;

                                $email_subscriber->save();

                                $check_already_exist = EmailSubscriberCategory::where('email_subscriber_id', $email_subscriber->id )
                                    ->where('category_id', $category_id)->first();

                                if(empty($check_already_exist)){
                                    $email_subscriber_category = new EmailSubscriberCategory();
                                    $email_subscriber_category->email_subscriber_id = $email_subscriber->id;
                                    $email_subscriber_category->category_id = $category_id;
                                    $email_subscriber_category->save();
                                }
                                $current_attempt++;
                            }
                        }
                    }
                }
                sleep($average_sleep_time);
            }
        };

        return $current_attempt;
    }
}
