<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use App\User;
use Carbon\Carbon;

class ArticleAlert extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'article:alert';

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
        $last_article = Article::where('status', true)->orderBy('created_at', 'desc')->first();
        $last_article_published_at = Carbon::parse($last_article->created_at)->format('l jS \\of F Y ');
        $created = new Carbon($last_article->created_at);
        $now = Carbon::now();
        $difference = ($created->diff($now)->days);

        $send_call = null;

        if($difference >= 1){
            $accountId = env('twilioKeyAccountId');
            $token = env('twilioKeySecret');
            $fromNumber = config('constants.twilio_from_number');

            $admin_users = User::whereIn('id', [1,2])->get();

            foreach($admin_users as $user){
                $voice_message = ",.. Hi, $user->name. Hope you are fine. I am from online books review. "
                    . " Last time you have published article on ". $last_article_published_at
                    . ". Please publish new article, as soon as possible. Bye Bye. Take care.";

                $phone_no = $user->phone;
                $twilio = new \Aloha\Twilio\Twilio($accountId, $token, $fromNumber);
                $send_call = $twilio->call($phone_no, function ($message) use ($voice_message) {
                    $message->say(str_repeat($voice_message, 2), ['voice' => 'woman', 'language' => 'en']);
                });

                $task_description = 'article_shortage';
                $short_message = $voice_message;
                $task_completed = true;
                $notification_type_id = 1;
                $user_id = $user->id;

                $scheduler_job = new SchedulerJob();
                $scheduler_job->task_description = $task_description;
                $scheduler_job->short_message = $short_message;
                $scheduler_job->task_completed = $task_completed;
                $scheduler_job->notification_type_id = $notification_type_id;
                $scheduler_job->user_id = $user_id;
                $scheduler_job->transaction_no = $send_call->sid;
                $scheduler_job->save();
            }
        }
    }
}
