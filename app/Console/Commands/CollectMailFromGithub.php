<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CollectMailQueue;
use Session;
use App\Models\EmailSubscriber;
use App\Models\EmailSubscriberCategory;
use Mail;

class CollectMailFromGithub extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:collect_mail_from_github';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $collect_mail_queues = CollectMailQueue::where('run_cron_job', true)->orderByRaw("RAND()")->get();

        $total_request_attempt = CollectMailQueue::where('run_cron_job', true)->sum('limit_cron_job_attempt');

        $one_day = 86400; //second
//        $average_sleep_time = $one_day/$total_request_attempt;
        $average_sleep_time = 1;
        $total_email_limit = 8000;
        $per_page = 100;
        $total_attempt = [];
        $total_attempt['total'] = 0;
        $count = 1;

        for($queue_system_loop = 0; $queue_system_loop < $count; $queue_system_loop++ ){
            $status = false;
            foreach($collect_mail_queues as $queue_item){
                $topic = $queue_item->topic;
                $category_id = $queue_item->category_id;
                $collect_mail_queue_id = $queue_item->id;
                $current_attempt_limit = $queue_item->limit_cron_job_attempt;

                if(!isset($total_attempt[$queue_item->id])){
                    $total_attempt[$queue_item->id] = 0;
                }

                if(($total_attempt[$queue_item->id] < $current_attempt_limit) && ($total_attempt['total'] <= $total_email_limit) ){
                    if($current_attempt_limit >= $total_email_limit){
                        $current_attempt_limit = $total_email_limit;
                    }
                    $attempt_status = $this->ping_github($topic, $category_id, $per_page, $average_sleep_time, $collect_mail_queue_id, $current_attempt_limit);
                    $total_attempt[$queue_item->id] += $attempt_status['current'];
                    $total_attempt['total'] += $attempt_status['total'];
                    $status = true;

                    $queue_item->run_count += 1;
                    $queue_item->save();
                }
            }

            if($status){
                $count++;
            }
        }

        $total_attempt['count'] = $count;

        Mail::send(['html'=>'mail_template.collect_mail_queue'], [], function ($message)
        {
            $message->subject('Cron job is finished for today');
            $message->from('info@onlinebooksreview.com', 'OnlineBooksReview');

            $message->to('mashpysays@gmail.com');
        });

        dd($total_attempt);
    }

    public function ping_github($topic, $category_id, $per_page, $average_sleep_time, $collect_mail_queue_id, $current_attempt_limit){
        $access_token = env('githubToken');
        $client = new \GuzzleHttp\Client();
        $email = [];
        $username_array = [];
        $topic = str_replace(' ', '+', $topic);
        $attempt_status['current'] = 0;
        $attempt_status['total'] = 0;

        $search_url = $client->get('https://api.github.com/search/code?per_page='. $per_page .'&order=desc&q='. $topic .'&sort=indexed&access_token='. $access_token);
        $event_json =  json_decode($search_url->getBody());

        foreach($event_json->items as $search_item) {
            $username = $search_item->repository->owner->login;

            if(!in_array($username, $username_array)){
                $username_array[] = $username;
                $event_url = $client->get('https://api.github.com/users/'. $username .'/events/public?access_token='. $access_token);
                $event_json =  json_decode($event_url->getBody());
                foreach($event_json as $item){
                    if(isset($item->payload->commits[0]) && ($attempt_status['current'] < $current_attempt_limit)){
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
                                $email_subscriber->source = 'g_'.$username.'_'.$topic;  // from our site
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
                                $attempt_status['current']++;
                                break;
                            }
                        }
                    }
                }
                sleep($average_sleep_time);
                $attempt_status['total']++;
            }
        };

        return $attempt_status;
    }
}
