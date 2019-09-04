<?php

namespace App\Console\Commands;

use App\Models\Article;
use App\Models\EmailSubscriber;
use Mail;
use DB;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SubscriptionMailSent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:name';

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
        $date = Carbon::today()->subDays(30);
        $article_category_id_ = Article::where('created_at', '>=', $date)->pluck('category_id');
        $email_subscribers = EmailSubscriber::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();
        foreach ($email_subscribers as $email_subscriber){
            $subscriber_category_id = EmailSubscriberCategory::where('email_subscriber_id',$email_subscriber->id)->pluck('category_id');
            $article_category_array = $article_category_id_->toArray();
            $subscriber_category_array = $subscriber_category_id->toArray();
            $favourite_category_id = array_intersect($article_category_array,$subscriber_category_array);
            if (!empty($favourite_category_id)){
                $favourite_articles = Article::where('category_id',$favourite_category_id)->get();
                Mail::send('mail_template.subscription_mail_send', ['favourite_articles'=>$favourite_articles], function ($message) use ($email_subscriber)
                {
                    $message->subject('newsletter');
                    $message->from('info@namespaceit.com', 'OnlineBooksReview');
                    $message->to($email_subscriber->email);
                });
                sleep(3);
            }
        }
    }
}
