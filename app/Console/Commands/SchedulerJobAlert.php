<?php

namespace App\Console\Commands;

use App\Models\SchedulerJob;
use Illuminate\Console\Command;
use App\Models\Article;
use App\User;
use Carbon\Carbon;

class SchedulerJobAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduler_job:alert';

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
        $unfinished_scheduler_jobs = SchedulerJob::where('task_completed', false)->get();
        $send_call = null;

        foreach($unfinished_scheduler_jobs as $item){
            $short_message = $item->short_message;
            $voice_message = $short_message;
            $notification_interval = $item->notification_interval;
            $deadline = $item->deadline;
            $created = new Carbon($deadline);
            $now = Carbon::now();
            $diff_in_hours = $now->diffInHours($created);

            if($diff_in_hours % $notification_interval == 0){
                $article_id = $item->article_id;
                $user_id = $item->user_id;
                $phone_no = $item->phone_no;
                $article = null;
                $username = 'Dear,';

                if(empty($phone_no)){
                    $user = User::find($user_id);
                    if(!empty($user)){
                        $phone_no = $user->phone;
                        $username = $user->name;
                    }
                }

                if(!empty($article_id)){
                    $article = Article::find($article_id);
                    $voice_message = ",.. Hi, $username. Hope you are fine. I am from online books review. "
                        . " You task was to work on ". $article->title
                        . ". Please try to complete this article as soon as possible and inform to Mashpy. Bye Bye. Take care.";
                } //if you update here voice message, also update on AdminSchedulerJobsController.php controller.

                if(!empty($short_message)){
                    $voice_message = $short_message;
                }

                if(!empty($phone_no) && !empty($voice_message)){
                    $accountId = env('twilioKeyAccountId');

                    $token = env('twilioKeySecret');
                    $fromNumber = config('constants.twilio_from_number');

                    $twilio = new \Aloha\Twilio\Twilio($accountId, $token, $fromNumber);
                    $send_call = $twilio->call($phone_no, function ($message) use ($voice_message) {
                        $message->say(str_repeat($voice_message, 2), ['voice' => 'woman', 'language' => 'en']);
                    });

                    //Please remove it later.
                    $admin_number = config('constants.admin_number');
                    if ($phone_no != $admin_number || $phone_no != '+8801823387518'){
                        $voice_message = 'Copy call to you.'. $voice_message;
                        $call_to_admin = $twilio->call($admin_number, function ($message) use ($voice_message) {
                            $message->say(str_repeat($voice_message, 2), ['voice' => 'woman', 'language' => 'en']);
                        });
                    }
                    //Please remove it later.

                    if($diff_in_hours == 0){
                        $item->task_completed = true;
                    }

                    $item->transaction_no = $send_call->sid;
                    $item->last_notification = Carbon::now();
                    $item->count += 1;
                    $item->update();
                }
            }

        }


    }
}
